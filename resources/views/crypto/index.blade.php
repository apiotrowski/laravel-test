<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Crypto</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

    <link rel="stylesheet" href={{ asset('css/app.css') }}>
</head>
<body>

<div class="container">
    @if ($errorMessage)
    <div class="alert alert-danger">
        {{ $errorMessage }}
    </div>
    @endif


    <div class="row">
        <form>
            <div class="row">
                <div class="col-md-6">
                    <label for="currencyTo" class="form-label">Select currency:</label>
                    <select class="form-select" id="currencyTo">
                        <option selected>{{ $currentCurrencyTo }}</option>
                        @foreach ($currencyToList as $currencyTo)
                            <option value="{{ $currencyTo }}">{{ $currencyTo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="date" class="form-label">Select date:</label>
                    <select class="form-select" id="date">
                        <option selected>{{ $currentDate }}</option>
                        @foreach ($dateList as $date)
                            <option value="{{ $date }}">{{ $date }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <div class="col-md-12">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Symbol</th>
                    <th>Opening Price</th>
                    <th>Closing Price</th>
                    <th>Change %</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($currencyRateStatusList as $currencyRateStatus)
                    <tr>
                        <td>{{ $currencyRateStatus->getCurrencyName() }}</td>
                        <td>{{ $currencyRateStatus->getCurrencySymbol() }}</td>
                        <td>
                            @if ($currencyRateStatus->isValid())
                                {{ $currencyRateStatus->getOpeningValueAsString() }}
                            @else
                                N\A
                            @endif
                        </td>
                        <td>
                            @if ($currencyRateStatus->isValid())
                            {{ $currencyRateStatus->getCloseValueAsString() }}
                            @else
                            N\A
                            @endif
                        </td>
                        <td
                            @if ($currencyRateStatus->isValid())
                                @if ($currencyRateStatus->getOpenCloseRatio() >= 0)
                                    class="green"
                                @else
                                    class="red"
                                @endif
                            @endif
                        >
                            @if ($currencyRateStatus->isValid())
                                {{ $currencyRateStatus->getOpenCloseRatio() }}
                            @else
                                N\A
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function submitForm () {
        const currencyTo = document.getElementById('currencyTo').value;
        const date = document.getElementById('date').value;

        window.location.href = `/crypto/${currencyTo}/${date}`;
    }

    document.getElementById('currencyTo').addEventListener('change', submitForm);
    document.getElementById('date').addEventListener('change', submitForm);
</script>
</body>
</html>
