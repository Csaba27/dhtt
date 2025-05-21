<div>
    <x-dhtt.breadcrumb :items="[
        ['label' => 'DHTT', 'url' => route('admin.dhtt.index')],
        ['label' => 'Események', 'url' =>  route('admin.dhtt.index', ['tab' => 'events'])],
        ['label' =>  ($isEdit ? 'Szerkesztés' : 'Létrehozás')]
    ]"/>
    <x-dhtt.step-navigation :steps="$steps"/>
</div>
