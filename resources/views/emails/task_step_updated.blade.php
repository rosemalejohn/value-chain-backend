@component('mail::message')
# Hi,

The task has been moved to {{ $task->step->description() }}.

The {{ config('app.name') }} Team. <br>
@endcomponent
