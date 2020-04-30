<?php
/**
 * File: AbstractExporter.php
 * Created: 28-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Exporter;

use Project\Models\Order;
use Project\Models\Profile;
use Project\Models\Subscription;
use Project\Models\User;
use Story\Collection;
use Story\ORM;

/**
 * Class AbstractExporter
 * @package Project\Services\Exporter
 */
abstract class AbstractExporter implements ExporterInterface
{
    /**
     * @var array|mixed
     */
    public $countries = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        // load the countries
        $this->countries = require SP . 'config/countries.php';
    }

    /**
     * Gets the users based on the items
     *
     * @param Collection $items
     *
     * @return Collection
     */
    public function getUsers(Collection $items)
    {
        $user_ids = $items->lists('user_id');

        if (!count($user_ids)) {
            return new Collection;
        }

        $user_ids = array_unique($user_ids);

        $users = $this->getUserModels($user_ids);
        $this->associateUserProfiles($users, $this->getUserProfilesModels($user_ids));

        // associate the users to the items
        foreach ($items as $item) {
            $item->user = $users->findBy('id', $item->user_id);
        }

        return $users;
    }

    /**
     * Gets the user models
     *
     * @param $user_ids
     *
     * @return Collection
     */
    public function getUserModels($user_ids)
    {
        $db = User::$db;
        $users = User::all(array("{$db->i('id')} IN (" . implode(',', $user_ids) . ")"));
        if ($users) {
            foreach ($users as $k => $row) {
                $users[$k] = new User($row);
            }
        }

        return new Collection($users);
    }

    /**
     * @param Collection $users
     * @param Collection $profiles
     */
    public function associateUserProfiles(Collection $users, Collection $profiles)
    {
        // associate the profiles to the users
        foreach ($profiles as $profile) {
            $user = $users->findBy('id', $profile->user_id);
            if (!isset($user->related['profiles'])) {
                $user->related['profiles'] = new Collection();
            }
            $user->profiles->push($profile);
        }
    }

    /**
     * Gets the profiles models
     *
     * @param $user_ids
     *
     * @return Collection
     */
    public function getUserProfilesModels($user_ids)
    {
        $db = Profile::$db;
        // get the profiles
        $profiles = Profile::all(array("{$db->i('user_id')} IN (" . implode(',', $user_ids) . ")"));
        if ($profiles) {
            foreach ($profiles as $k => $row) {
                $profiles[$k] = new Profile($row);
            }
        }

        return new Collection($profiles);
    }

    /**
     * Builds the headers
     *
     * @param $columns
     *
     * @return Collection
     */
    public function buildHeaders($columns)
    {
        $headers = array();
        $all_columns = $this->getColumns();
        foreach ($columns as $column) {
            $headers[] = $all_columns[$column];
        }

        return new Collection($headers);
    }

    /**
     * Builds the amount cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildAmountCell(ORM $item)
    {
        return get_formatted_currency($item->amount);
    }

    /**
     * Builds the order id cell
     *
     * @param Order|Subscription $item
     *
     * @return string
     */
    public function buildOrderIdCell($item)
    {
        return $item->order_id ? Order::generateOrderId($item->order_id) : false;
    }

    /**
     * Builds the status cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildStatusCell(ORM $item)
    {
        return $item->status ? _('Enabled') : _('Disabled');
    }

    /**
     * Builds the user cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserCell(ORM $item)
    {

        // add the name
        if (!$item->user) {
            return false;
        }
        $return = "";

        /** @var Collection $profiles */
        $profiles = $item->user->profiles;
        // name
        $name = $profiles->findBy('name', 'name');
        $return .= $name ? $name->value : '';
        // email
        $return .= "\nEmail: {$item->user->email}\n";

        // phone
        $phone = $profiles->findBy('name', 'phone');
        $return .= $phone ? "\nPhone: " . $phone->value . "\n" : '';


        // address
        $address = $profiles->findBy('name', 'address');
        $return .= $address ? "\n" . $address->value : '';
        $address2 = $profiles->findBy('name', 'address2');
        $return .= $address2 ? "\n" . $address2->value : '';
        $city = $profiles->findBy('name', 'city');
        $return .= $city ? "\n" . $city->value : '';
        $state = $profiles->findBy('name', 'state');
        $return .= $state ? " " . $state->value : '';
        $zip = $profiles->findBy('name', 'zip');
        $return .= $zip ? " " . $zip->value : '';
        // country
        $country = $profiles->findBy('name', 'country');
        $return .= $country ? "\n" .
            (isset($this->countries[$country->value]) ? $this->countries[$country->value] : $country->value) : '';

        return trim($return);
    }

    /**
     * Builds the user name cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserNameCell(ORM $item)
    {

        // add the name
        if (!$item->user) {
            return false;
        }

        /** @var Collection $profiles */
        $profiles = $item->user->profiles;

        // name
        $name = $profiles->findBy('name', 'name');

        return $name ? $name->value : '';
    }

    /**
     * Builds the user email cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserEmailCell(ORM $item)
    {
        if (!$item->user) {
            return false;
        }

        return $item->user->email;
    }

    /**
     * Builds the user address cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserAddressCell(ORM $item)
    {

        // add the name
        if (!$item->user) {
            return false;
        }
        $return = "";

        /** @var Collection $profiles */
        $profiles = $item->user->profiles;

        // address
        $address = $profiles->findBy('name', 'address');
        $return .= $address ? "\n" . $address->value : '';
        $address2 = $profiles->findBy('name', 'address2');
        $return .= $address2 ? "\n" . $address2->value : '';
        $city = $profiles->findBy('name', 'city');
        $return .= $city ? "\n" . $city->value : '';
        $state = $profiles->findBy('name', 'state');
        $return .= $state ? " " . $state->value : '';
        $zip = $profiles->findBy('name', 'zip');
        $return .= $zip ? " " . $zip->value : '';
        // country
        $country = $profiles->findBy('name', 'country');
        $return .= $country ? "\n" .
            (isset($this->countries[$country->value]) ? $this->countries[$country->value] : $country->value) : '';

        return trim($return);
    }

    /**
     * Builds the user phone cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserPhoneCell(ORM $item)
    {

        // add the name
        if (!$item->user) {
            return false;
        }
        $return = "";

        /** @var Collection $profiles */
        $profiles = $item->user->profiles;

        // phone
        $phone = $profiles->findBy('name', 'phone');
        $return .= $phone ? $phone->value : '';

        return $return;
    }
}
