<div>
    <x-dhtt.breadcrumb :items="[
        ['label' => 'DHTT', 'url' => route('admin.dhtt.index')],
        ['label' => 'Túrák', 'url' =>  route('admin.dhtt.index', ['tab' => 'hikes'])],
        ['label' =>  ($isEdit ? 'Szerkesztés' : 'Létrehozás')]
    ]"/>
    <x-dhtt.step-navigation :steps="$steps"/>
</div>
