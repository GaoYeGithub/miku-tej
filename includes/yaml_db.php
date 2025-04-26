<?php
// YAML Database
require_once 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

define('YAML_DB_PATH', __DIR__ . '/data/users.yaml');

function getUsers() {
    try {
        if (!file_exists(YAML_DB_PATH)) {
            $dir = dirname(YAML_DB_PATH);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // default users
            $default_users = [
                'users' => [
                    [
                        'username' => 'plz-work',
                        'password' => '123',
                        'favorite_song' => ''
                    ]
                ]
            ];
            
            file_put_contents(YAML_DB_PATH, Yaml::dump($default_users, 4));
            return $default_users['users'];
        }
        
        $data = Yaml::parseFile(YAML_DB_PATH);
        return isset($data['users']) ? $data['users'] : [];
    } catch (Exception $e) {
        error_log('Error reading YAML database: ' . $e->getMessage());
        return [];
    }
}

function addUser($username, $password, $favorite_song = null) {
    try {
        // Get current users
        $data = [];
        if (file_exists(YAML_DB_PATH)) {
            $data = Yaml::parseFile(YAML_DB_PATH);
        } else {
            $data = ['users' => []];
        }
        
        // Check username
        if (userExists($username)) {
            return false;
        }
        
        // Add new user
        $data['users'][] = [
            'username' => $username,
            'password' => $password,
            'favorite_song' => empty($favorite_song) ? null : $favorite_song
        ];
        
        // Save
        file_put_contents(YAML_DB_PATH, Yaml::dump($data, 4));
        return true;
    } catch (Exception $e) {
        error_log('Error adding user to YAML database: ' . $e->getMessage());
        return false;
    }
}

function userExists($username) {
    $users = getUsers();
    
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return true;
        }
    }
    
    return false;
}


function validateUser($username, $password) {
    $users = getUsers();
    
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            return $user;
        }
    }
    
    return false;
}
?>