<?php namespace Adtrak\Logger\Controllers;

use Adtrak\Logger\Models\Log;

class LogController
{
    private $user;

    public function __construct()
    {
        // $this->addActions();

        // $this->user = (object) [
        //     'name' => get_current_user(),
        //     'ID' => \get_current_user_id()
        // ];
        //
        // $user = \wp_get_current_user();
        // dd($user);
    }

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

        $description = "<b>{$this->user->name}</b> created {$postType} <b>#{$post_id}</b>.";

        $log = new Log;
        $log->user_id = $this->user->ID;
        $log->ip = $this->findUserIP();
        $log->type = "New {$postType}";
        $log->description = $description;
        $log->save();
    }

    public function editPost($post_id, $post)
    {
        $postType = get_post_type_object(get_post_type($post));
        $postType = $postType->labels->singular_name;

        switch ($post->post_status) {
            case 'trash':
                $description = "<b>{$this->user->name}</b> moved {$postType} <b>#{$post_id}</b> to the trash.";
                $type = "Trashed {$postType}";
                break;
            case 'public':
                $description = "<b>{$this->user->name}</b> published {$postType} <b>#{$post_id}</b>.";
                $type = "Published {$postType}";
                break;
            case 'private':
                $description = "<b>{$this->user->name}</b> made {$postType} <b>#{$post_id}</b> private.";
                $type = "Updated {$postType}";
                break;
            default:
                $description = "<b>{$this->user->name}</b> made updates to {$postType} <b>#{$post_id}</b>";
                $type = "Updated {$postType}";
                break;
        }

        $log = new Log;
        $log->user_id = $this->user->ID;
        $log->ip = $this->findUserIP();
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
            $log->ip = $this->findUserIP();
            $log->type = "Deleted {$postType}";
            $log->description = "<b>{$this->user->name}</b> deleted {$postType} <b>#{$post_id}</b>.";
            $log->save();
        }
    }

    public function switchTheme($new_name, $new_theme)
    {
        $log = new Log;
        $log->user_id = $this->user->ID;
        $log->ip = $this->findUserIP();
        $log->type = "Switched Theme";
        $log->description = "<b>{$this->user->name}</b> switched theme to \"{$new_theme}\".";
        $log->save();
    }

    public function userLogin($user_login, $user)
    {
        $log = new Log;
        $log->user_id = $user->ID;
        $log->ip = $this->findUserIP();
        $log->type = "Login";
        $log->description = "<b>{$user_login}</b> logged in.";
        $log->save();
    }

    /**
     * HELPERS
     */

    public function findUserIP()
    {
        return getenv('HTTP_CLIENT_IP')?:
                getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                getenv('HTTP_FORWARDED_FOR')?:
                getenv('HTTP_FORWARDED')?:
                getenv('REMOTE_ADDR');
    }
}
