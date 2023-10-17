<x-manager-action-section>
    <x-slot name="title">
        Goals
    </x-slot>

    <x-slot name="description">
        Here you can find your goals for this bank account.
    </x-slot>

    <x-slot name="content">
        <x-jet-button wire:click="createNewGoal" wire:loading.attr="disabled">
            Create
        </x-jet-button>
        <br><br>

        <x-jet-action-message class="mr-3" on="created">
            <div class="font-medium text-green-600">The target has been created.</div>
            <br>
        </x-jet-action-message>

        <x-jet-action-message class="mr-3" on="completed">
            <div class="font-medium text-green-600">The objective has been completed.</div>
            <br>
        </x-jet-action-message>

        <x-jet-action-message class="mr-3" on="deleted">
            <div class="font-medium text-green-600">The target has been deleted.</div>
            <br>
        </x-jet-action-message>

        <table class="border-collapse w-full">
            <thead>
            <tr>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">
                    Created At
                </th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">
                    Completed
                </th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">
                    Goal ($)
                </th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">
                    Last Account Balance ($)
                </th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">
                    Reached (%)
                </th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">
                    Completed?
                </th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">
                    Time passed (days)
                </th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($this->goals as $goal)
                <tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Company name</span>
                        {{ \Carbon\Carbon::parse($goal->startedAt)->format('d.m.Y') }}
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b text-center block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Country</span>
                        @if ($goal->endedAt !== null)
                            {{ \Carbon\Carbon::parse($goal->endedAt)->format('d.m.Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b text-center block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Status</span>
                        $ {{ number_format($goal->goal, 2, ',', '.') }}
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b text-center block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Status</span>
                        $ {{ number_format($goal->lastBalance, 2, ',', '.') }}
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b text-center block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Status</span>
                        {{ number_format($goal->calculateReachedPercentage(), 2, ',', '.') }} %
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b text-center block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Status</span>
                        @if ($goal->endedAt !== null)
                            <div class="font-green-500">Yes</div>
                        @else
                            <div class="font-red-500">No</div>
                        @endif
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b text-center block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Status</span>
                        {{ \Carbon\Carbon::parse($goal->startedAt)->diffInDays($goal->endedAt) }}
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b text-center block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Actions</span>
                        @can('update', $goal)
                            @if ($goal->endedAt === null)
                                <a href="#" wire:click="completeGoal({{ $goal }})">
                                    <i class="fas fa-check"></i>
                                </a>
                            @endif
                        @endcan

                        @can('delete', $goal)
                            <a href="#" wire:click="deleteGoal({{ $goal }})">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <x-jet-dialog-modal wire:model="createGoal">
            <x-slot name="title">
                New Goal
            </x-slot>

            <x-slot name="content">
                <div class="col-span-6 sm:col-span-4" x-on:create-goal.window="setTimeout(() => $refs.startedAt.focus(), 250)">
                    <x-jet-label for="startedAt" value="Date"/>
                    <x-jet-input id="startedAt" type="date" maxlength="50" class="mt-1 block w-full"
                                 x-ref="startedAt"
                                 wire:model.defer="startedAt"
                                 wire:keydown.enter="submitGoal" />
                    <x-jet-input-error for="startedAt" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="goal" value="Goal"/>
                    <x-jet-input id="goal" type="text" maxlength="15" class="mt-1 block w-full"
                                 wire:model.defer="goal"
                                 wire:keydown.enter="submitGoal" />
                    <x-jet-input-error for="goal" class="mt-2"/>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('createGoal')" wire:loading.attr="disabled">
                    Cancel
                </x-jet-secondary-button>

                <x-jet-button class="ml-2" wire:click="submitGoal" wire:loading.attr="disabled">
                    Save
                </x-jet-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>
</x-manager-action-section>