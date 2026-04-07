@php
    $processedContent = e($comment->content);

    $adminUsers = Cache::remember('admin_users_for_mentions', 60, function () {
        $admins = \App\Models\User::where('role', 'admin')->get()->toArray();
        usort($admins, fn($a, $b) => strlen($b['name']) <=> strlen($a['name']));
        return $admins;
    });

    foreach ($adminUsers as $admin) {
        $mentionString = e('@' . $admin['name']);
        $link = '<a href="'. route('user.profile', $admin['name']) .'" class="font-bold text-red-500 hover:underline">' . $mentionString . '</a>';
        $processedContent = str_replace($mentionString, $link, $processedContent);
    }

    $processedContent = preg_replace_callback(
        '/(?<!\w)@(\w+)/',
        function ($matches) {
            $name = $matches[1];

            $user = \App\Models\User::where('name', $name)->where('role', '!=', 'admin')->first();

            if ($user) {
                return '<a href="'. route('user.profile', $user->name) .'" class="font-bold text-blue-500 hover:underline">@' . e($name) . '</a>';
            }

            return $matches[0];
        },
        $processedContent
    );
@endphp

<p class="mt-2 text-gray-700 dark:text-gray-300 break-words">{!! nl2br($processedContent) !!}</p>