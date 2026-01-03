@php
$findStep = \App\Managers\LookupManager::getWorkflowStep($approvalId,$moduleId);
@endphp

@dd($approvalId, $moduleId,$inititor->createuser->name,$findStep,$findApproval)

<div class="row mb-4">
    <div class="col-12">
        <ul class="workflow-steps">
            @foreach($workflowSteps as $index => $step)
                <li class="{{ $step['status'] }}">
                    <span class="step-circle">{{ $index + 1 }}</span>
                    <span class="step-title">{{ $step['title'] }}</span>
                    <small class="step-user">{{ $step['user'] }}</small>
                </li>
            @endforeach
        </ul>
    </div>
</div>
