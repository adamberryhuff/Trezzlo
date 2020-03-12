<?php

namespace App\Http\Controllers;

use Notification;
use App\Models\User\User;
use App\Models\Link\Link;
use Illuminate\Http\Request;
use App\Notifications\Slack;
use App\Models\Link\Instance as LinkInstance;

class RedirectController extends Controller
{
    /**
     * review - redirects the user clicking the review link
     *
     * @param string $data - the SR for link identification
     *
     * @return redirect
     */
    public function redirect ($data)
    {
        // log click
        $link = $this->getLink($data);
        $link->visits++;
        $link->save();

        // feedback link exception
        if ($link->link_id == Link::LINK_FEEDBACK) {
            return $this->redirectToFeedbackLink($data);
        } else {
            return $this->redirectToUserDefinedLink($link);
        }
    }

    /**
     * getLink - Obtains the link that was clicked from the database
     *
     * @param string $data - the unique string for this link
     *
     * @return object $link - Instance object
     */
    protected function getLink ($data)
    {
        $link = LinkInstance::where('url_id', $data)->orderBy('id', 'desc')->first();
        if (empty($link)) {
            $subject = 'Crawlers on the redirect URL!';
            $error   = __FILE__ . ' @ ' . __LINE__;
            $meta    = ['sr' => $data];
            Notification::send(User::first(), new Slack($subject, $error, $meta, Slack::WARNING));
            exit;
        }
        return $link;
    }

    /**
     * redirectToFeedbackLink - redirects the user to the feedback link URL
     *
     * @param string $data - the unique string for this link
     *
     * @return redirect
     */
    protected function redirectToFeedbackLink ($data)
    {
        $base = env('SERVER_HOST');
        if (env('APP_DEBUG')) {
            $base = env('LOCAL_HOST');
        }
        return redirect($base . 'f/' . $data);
    }

    /**
     * redirectToUserDefinedLink - redirect the user to a link the client provided
     *
     * @param object $link - the link object
     *
     * @return redirect
     */
    protected function redirectToUserDefinedLink ($link)
    {
        if (empty($link->link->redirect)) {
            $subject = 'Missing review redirect link!';
            $error   = __FILE__ . ' @ ' . __LINE__;
            $meta    = ['link' => $link];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            exit;
        }

        // redirect
        return redirect($link->link->redirect);
    }
}
