@component('mail::message')
# Hi,

The task has been moved to {{ $task->step->description() }}.

Task: {{ $task->title }} <br />
Description: {!! $task->description !!} <br />
Outcome: {!! $task->outcome !!} <br />
Priority: {{ optional($task->priority)->description() }} <br />
Impact: {{ optional($task->impact)->description() }} <br />

The {{ config('app.name') }} Team. <br>
@endcomponent
