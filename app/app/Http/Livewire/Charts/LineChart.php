<?php
namespace App\Http\Livewire\Charts;

use Livewire\Component;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Order;
use Carbon\Carbon;

class LineChart extends Component
{
    public $data;

    public function mount()
    {
        // إعداد البيانات التي ستُعرض في الرسم البياني
        $this->data = $this->getSalesData();
    }

    public function getSalesData()
    {
        // هنا يمكنك تحديد البيانات التي ستعرضها في الرسم البياني، على سبيل المثال:
        $salesData = Trend::model(Order::class)
            ->between(Carbon::now()->subMonth(), Carbon::now())
            ->perDay()
            ->count();

        return $salesData;
    }

    public function render()
    {
        return view('livewire.charts.line-chart');
    }
}

