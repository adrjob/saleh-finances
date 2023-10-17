<x-manager-action-section wire:poll="polling">
    <x-slot name="title">
        Details
    </x-slot>

    <x-slot name="description">
    Here you can see all the details listed briefly and concisely.
    </x-slot>

    <x-slot name="content">
        <div class="text-blue-500 font-bold">Average</div>

        <table class="table-auto">
            <tbody>
            <tr>
                <th>Bank Balance</th>
                <td>$ {{ number_format($this->averageBalance, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Difference ($)</th>
                <td>$ {{ number_format($this->averageBalanceDifferenceDollar, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Difference (%)</th>
                <td>{{ number_format($this->averageBalanceDifferencePercentage, 2, ',', '.') }} %</td>
            </tr>
            </tbody>
        </table>
    </x-slot>
</x-manager-action-section>