<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Quotation Invoice </title>


    <style type="text/css">
        body {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        }

        .logo {

            margin-right: 20px;
            width: 80px;
        }

        .qr-code {
            width: 120px;
        }

        .width-full {

            /*width: 216mm;*/
            margin-top: 10px;
            font-size: 12px;
            height: auto;
        }

        .box {
            width: 20px;
            height: 20px;
            border: 1px solid #000000;
            margin-left: -1px;
            text-align: center;
            float: left;
            display: block;
            margin-bottom: 2px;
            line-height: 20px;
        }

        .box-small {
            width: 14px;
            height: 21px;
            border: 1px solid #000000;
            margin-left: -1px;
            text-align: center;
            float: left;
            display: block;
            margin-bottom: 2px;
            line-height: 20px;
            font-size: 12px;
        }

        .boxes: last-child {
            border-right: 1px solid #000000;
        }

        #marks {

            font-size: 12px;
            border-collapse: collapse;
            width: 100%;
        }

        #marks td,
        #marks th {
            border: 1px solid #000000;
            padding: 6px 3px 6px 3px;
            text-align: center;
        }

        .photo {

            width: 100%;
            height: 100%;
            border: 1px solid black;
            text-align: left;
        }

        .basic {

            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }


        .basic td,
        .basic th {
            border: 1px solid #000000;
            padding: 6px 3px 6px 3px;
            text-align: left;
            /*width: 25%;*/
        }

        #photo {

            width: 35mm;
            height: 45mm;
            border: 1px solid #000000;
            position: absolute;
            right: 18mm;
            top: 60mm;
            border-radius: 10px;
        }

        #under-photo {

            width: 35mm;
            height: 20mm;
            border: 1px solid #000000;
            position: absolute;
            right: 18mm;
            top: 110mm;
            border-radius: 10px;
        }

        #fields {

            font-size: 12px;
        }

        #fields td {

            padding-top: 5px;
            padding-bottom: 5px;
        }

        #subjects {

            width: 85%;
            margin-top: 10px;
        }

        #subjects td {

            height: 25px;
            font-size: 12px;
        }

        li {
            font-size: 12px;
        }

        #signature {
            float: right;
            font-size: 12px;

        }

        .footer {
            font-size: 10px;
        }
    </style>
</head>


<body>

    <table style="width: 100%;">
        <tr>
            <td style="width: 30%" rowspan="4">
                <img style="width: 90%" class="logo" src="data:image/png;base64,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">
            </td>
            <td>
            </td>

        </tr>
    </table>

    <table class="basic">
        <tr>
            <th>Name</th>
            <td>
                {{$quotation['clientName'] ?? 'N/A'}}
            </td>
            <th>Contact</th>
            <td>
                {{$quotation['clientContact'] ?? 'N/A'}}
            </td>
            <th>E-Mail</th>
            <td>
                {{$quotation['clientEmail'] ?? 'N/A'}}
            </td>
        </tr>
        </tr>
        <tr>
            <th>Validity</th>
            <td>
                {{ $quotation->validity->format('M j, Y') ?? 'N/A' }}
            </td>
            <th>Adults</th>
            <td>
                {{$quotation['adults'] ?? 'N/A'}}
            </td>
            <th>Childern</th>
            <td>
                {{$quotation['children'] ?? 'N/A'}}
            </td>
        </tr>
        <tr>
            <th>City</th>
            <td>
                {{ $city ?? 'N/A' }}
            </td>
            <th>Markup Type</th>
            <td>
                {{ strtoupper($quotation->markupType) ?? 'N/A' }}
            </td>
            <th>Preferred Dates</th>
            <td>
                {{ $quotation->tourFrom->format('M j, Y') ?? 'N/A' }} to {{ $quotation->tourEnd->format('M j, Y') ?? 'N/A' }}
            </td>

        </tr>

    </table>


    <br>
    <table class="basic">
        <tr>
            <th style="width: 5%">Sr#</th>
            <th>Description</th>
            <th style="width: 15%">Paid Date</th>
            <th style="width: 15%">Paid Amount</th>
            <th style="width: 18%">Remaining Amount</th>
        </tr>
        @forelse ($quotation->quotationInvoices as $k => $invoice)
        <tr>
            <td>{{ $k+1 }}</td>
            <td></td>
            <td style="text-align:center;">@if(!empty($quotation->quotationInvoices[0]->invoiceDate)){{date('d M, Y | h:i A', strtotime($quotation->quotationInvoices[0]->invoiceDate)) }} @else N/A @endif</td>
            <td>{{ number_format($invoice->dueAmount) ?? 'N/A' }}</td>
            <td>{{ number_format($invoice->remainingAmount) ?? 'N/A' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align:center;">No Records Found</td>
        </tr>
        @endforelse

    </table>
    <br>
    <table class="basic">
        <tr>
            <th>Total Amount</th>
            <td>
                {{ $totalAmount ?? 'N/A' }}
            </td>
            <th>Total Remaining</th>
            <td>
                {{ $totalRemaining ?? 'N/A' }}
            </td>

        </tr>

    </table>
    <!-- <ul>
        <li>NO MASK NO ENTRY ( FOLLOW THE SOPs of COVID 19)</li>
    </ul> -->





</body>

</html>