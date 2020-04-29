<?php
/**
 * File: seeds.php
 * Created: 27-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */


// list of seeders
return array(
    // config
    'Project\Seeds\ConfigSeeder',
    // users
    'Project\Seeds\UserSeeder',
    // profiles
    'Project\Seeds\ProfileSeeder',
    // default roles
    'Project\Seeds\RoleSeeder',
    // user roles
    'Project\Seeds\UserRolesSeeder',

    // submission statuses
    'Project\Seeds\SubmissionStatusSeeder',

    // subscription categories seeder
    'Project\Seeds\SubscriptionCategoriesSeeder',

    // email templates
    'Project\Seeds\TemplatesSeeder',

    // snippets seeder
    'Project\Seeds\SnippetsSeeder',

    // menus seeder
    'Project\Seeds\MenusSeeder',

    // pages seeder
    'Project\Seeds\PagesSeeder',
    'Project\Seeds\PagesContentSeeder',
);
