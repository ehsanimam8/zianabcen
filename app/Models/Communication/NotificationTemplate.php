<?php

namespace App\Models\Communication;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class NotificationTemplate extends Model
{
    use HasUuids;

    protected $guarded = [];

    /**
     * Replace dynamic tags in the template subject and body.
     *
     * Supported tags: {{student_name}}, {{roll_number}}, {{email}}, {{date}}
     * Additional tags can be passed via $data as ['{{tag}}' => 'value'].
     *
     * @return array{subject: string, body: string}
     */
    public function resolveTagsFor(User $user, array $data = []): array
    {
        $defaultTags = [
            '{{student_name}}' => $user->name,
            '{{roll_number}}'  => $user->roll_number ?? 'N/A',
            '{{email}}'        => $user->email,
            '{{date}}'         => now()->format('F j, Y'),
        ];

        $tags = array_merge($defaultTags, $data);

        $subject = str_replace(array_keys($tags), array_values($tags), $this->subject ?? '');
        $body    = str_replace(array_keys($tags), array_values($tags), $this->body ?? '');

        return compact('subject', 'body');
    }

    /**
     * Resolve tags and send a plain-HTML email to the given user.
     */
    public function send(User $recipient, array $data = []): void
    {
        $resolved = $this->resolveTagsFor($recipient, $data);

        Mail::send([], [], static function ($message) use ($recipient, $resolved) {
            $message
                ->to($recipient->email, $recipient->name)
                ->subject($resolved['subject'])
                ->html($resolved['body']);
        });
    }
}
