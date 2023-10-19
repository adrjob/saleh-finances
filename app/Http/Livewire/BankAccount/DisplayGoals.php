<?php

namespace App\Http\Livewire\BankAccount;

use App\Models\BankAccount;
use App\Models\BankAccount\Goal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 * Class DisplayGoals
 *
 * @package App\Http\Livewire\BankAccount
 */
class DisplayGoals extends Component
{
    public BankAccount $bankAccount;

    public bool $createGoal = false;

    public ?string $startedAt;
    public ?float $goal;

    public function render()
    {
        return view('livewire.bank-account.display-goals');
    }

    public function createNewGoal(): void
    {
        $this->startedAt = Carbon::parse(time())->format('Y-m-d');
        $this->goal = 0;

        $this->dispatchBrowserEvent('create-goal');
        $this->createGoal = true;
    }

    public function submitGoal(): void
    {
        $this->validate(
            [
                'startedAt' => 'required|date',
                'goal' => 'required|numeric'
            ],
            [
                'required' => 'The :attribute field must be filled out.',
                'date' => 'The :attribute field does not contain a valid date.',
                'numeric' => 'The :attribute field does not contain a number.'
            ],
            [
                'startedAt' => 'Started am',
                'goal' => 'Goal'
            ]
        );

        $openGoalCount = $this->bankAccount
            ->goals()
            ->whereNull('endedAt')
            ->count();
        $newerGoalCount = $this->bankAccount
            ->goals()
            ->whereDate('startedAt', '>', $this->startedAt)
            ->whereDate('endedAt', '>', $this->startedAt, 'or')
            ->count();

        if ($openGoalCount > 0) {
            $this->addError(
                'startedAt',
                'The destination could not be created: another destination is already open.'
            );

            return;
        }

        if ($newerGoalCount > 0) {
            $this->addError(
                'startedAt',
                'The goal could not be created: A goal already exists that was started/finished after this one (from a date perspective).'
            );

            return;
        }

        $this->bankAccount->addGoal($this->startedAt, $this->goal);

        $this->createGoal = false;
        $this->emit('created');
    }

    public function completeGoal(Goal $goal): void
    {
        if ($goal->endedAt === null) {
            $goal->endedAt = Carbon::now()->format('Y-m-d');
            $goal->updateLastBalance();
            $goal->save();

            $this->emit('completed');
        }
    }

    public function deleteGoal(Goal $goal): void
    {
        $goal->delete();

        $this->emit('deleted');
    }

    /**
     * @return Collection
     */
    public function getGoalsProperty(): Collection
    {
        return $this->bankAccount->goals()
            ->orderByDesc('startedAt')
            ->orderByDesc('created_at')
            ->get();
    }
}
