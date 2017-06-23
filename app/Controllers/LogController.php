<?php namespace Adtrak\Logger\Controllers;

use Adtrak\Logger\Models\Log;

class LogController
{
    private $user;

    public function __construct() {}

    public function getUserData()
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $this->user = (object) [
                'name' => $user->data->user_login,
                'ID'   => $user->ID
            ];
        }
    }

    public function savePost($post_id, $post)
    {
        // dd($post);
        if (! (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)))
            return;

        $postType = get_post_type_object(get_post_type($post));
        $postType = $postType->labels->singular_name;

        $description = "{$this->user->name} created {$postType} #{$post_id}.";

        $log = new Log;
        $log->user_id = $this->user->ID;
        $log->ip = $this->_findUserIP();
        $log->type = "New {$postType}";
        $log->description = $description;
        $log->save();
    }

    public function editPost($post_id, $post)
    {
        $postType = get_post_type_object(get_post_type($post));
        $postType = $postType->labels->singular_name;
        $postTitle = $this->_getDraftOrTitle($post_id);

        switch ($post->post_status) {
            case 'trash':
                $description = "{$this->user->name} moved \"{$postTitle} (#{$post_id})\" to the trash.";
                $type = "Trashed {$postType}";
                break;
            case 'public':
                $description = "{$this->user->name} published \"{$postTitle} (#{$post_id})\".";
                $type = "Published {$postType}";
                break;
            case 'private':
                $description = "{$this->user->name} made \"{$postTitle} (#{$post_id})\" private.";
                $type = "Updated {$postType}";
                break;
            default:
                $description = "{$this->user->name} made updates to \"{$postTitle} (#{$post_id})\".";
                $type = "Updated {$postType}";
                break;
        }

        $log = new Log;
        $log->user_id = $this->user->ID;
        $log->ip = $this->_findUserIP();
        $log->type = $type;
        $log->description = $description;
        $log->save();
    }

    public function deletePost($post_id)
    {
        // Needed to make sure this doesn't trigger twice.
        if (did_action('delete_post') === 1) {
            $postType = get_post_type_object(get_post_type($post));
            $postType = $postType->labels->singular_name;

            $log = new Log;
            $log->user_id = $this->user->ID;
            $log->ip = $this->_findUserIP();
            $log->type = "Deleted {$postType}";
            $log->description = "{$this->user->name} deleted \"{$postType} #{$post_id}\".";
            $log->save();
        }
    }

    public function switchTheme($new_name, $new_theme)
    {
        $log = new Log;
        $log->user_id = $this->user->ID;
        $log->ip = $this->_findUserIP();
        $log->type = "Switched Theme";
        $log->description = "{$this->user->name} switched theme to \"{$new_theme}\".";
        $log->save();
    }

    public function userLogin($user_login, $user)
    {
        $log = new Log;
        $log->user_id = $user->ID;
        $log->ip = $this->_findUserIP();
        $log->type = "Login";
        $log->description = "{$user_login} logged in.";
        $log->save();
    }

    public function activatedPlugin($plugin, $network_wide)
    {
        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);

        if('deactivated_plugin' === current_filter()) {
            $type = 'Deactivated Plugin';
            $desc = "{$this->user->name} deactivated \"{$plugin_data['Name']}\".";
        } else {
            $type = 'Activated Plugin';
            $desc = "{$this->user->name} activated \"{$plugin_data['Name']}\".";
        }

        $log = new Log;
        $log->user_id = $this->user->ID;
        $log->ip = $this->_findUserIP();
        $log->type = $type;
        $log->description = $desc;
        $log->save();
    }

    public function coreUpdated($wp_version)
    {
        global $pagenow;

        // Auto updated
        if ($pagenow !== 'update-core.php')
            $desc = 'WordPress Auto Updated';
        else
            $desc = 'WordPress Updated';

        $log = new Log;
        $log->user_id = $this->user->ID;
        $log->ip = $this->_findUserIP();
        $log->type = 'Core Update';
        $log->description = $desc;
        $log->save();
    }

    /**
     * HELPERS
     */

    public function _findUserIP()
    {
        $server_ip_keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($server_ip_keys as $key) {
            if (isset( $_SERVER[ $key ]) && filter_var($_SERVER[ $key ], FILTER_VALIDATE_IP)) {
                return $_SERVER[ $key ];
            }
        }

        // Fallback local ip.
        return '127.0.0.1';
    }

    public function _getDraftOrTitle()
    {
        $title = get_the_title($post);

        if (empty($title))
            return null;

        return $title;
    }
}
