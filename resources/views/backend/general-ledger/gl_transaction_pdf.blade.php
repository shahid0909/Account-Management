<div style="text-align: center;">
    <table style="border-collapse: collapse;width: 100%; table-layout: fixed;">
        <tr>
            <td style="width: 4%">
                {{--<img width="60px" height="60px" src="{{$data->image}}">--}}
            </td>
            <td style="text-align: center; width: 96%">
                <h2>GLOBAL ACCOUNTING SYSTEM</h2>
                <h6>TRANSACTION VOUCHER</h6>
            </td>
        </tr>
    </table>

    <hr>
</div>

<div>
    <table style="border-collapse: collapse;width: 100%; font-size: 13px; table-layout: fixed;">
        <tr>
            <th style="text-align: left; padding-left: 5px; padding-right: 5px">
                <p>Voucher Type:{{--{{$data->requested_by_name}}--}}</p>
            </th>
            <td style="text-align: left; padding-left: 5px; padding-right: 5px;">
                <p>Journal -({{$data->function_id}})</p>
            </td>
            <th style="text-align: left; padding-left: 90px; padding-right: 90px"></th>
            <th style="text-align: left; padding-left: 5px; padding-right: 5px">
                <p>Transaction Date:</p>
            </th>
            <td style="text-align: left; padding-left: 5px; padding-right: 5px;">
                <p>{{$data->trans_date}}</p>
            </td>

        </tr>
        <tr>
            <th style="text-align: left; padding-left: 5px; padding-right: 5px;">
                <p>Voucher no:</p>
            </th>
            <td style="text-align: left; padding-left: 5px; padding-right: 5px">
                <p></b>{{$data->document_no}}</p>
            </td>
            <th style="text-align: left; padding-left: 90px; padding-right: 90px"></th>
            <th style="text-align: left; padding-left: 5px; padding-right: 5px">
                <p>Voucher Date:</p>
            </th>
            <td style="text-align: left; padding-left: 5px; padding-right: 5px">
                <p>{{$data->document_date}}</p>
            </td>
        </tr>
    </table>
    <br>
    <table style="border-collapse: collapse;width: 100%; table-layout: fixed;font-size: 13px;" border="1">
        <thead>
        <tr>
            <th style="text-align: left; padding-left: 5px; padding-right: 5px" >SL</th>
            <th style="text-align: left; padding-left: 5px; padding-right: 5px" >Account ID</th>
            <th style="text-align: left; padding-left: 5px; padding-right: 5px" >COA Name</th>
            <th style="text-align: center; padding-left: 5px; padding-right: 5px">DR/CR</th>
            <th style="text-align: center; padding-left: 5px; padding-right: 5px">DR Amount</th>
            <th style="text-align: center; padding-left: 5px; padding-right: 5px">CR Amount</th>
        </tr>
        </thead>

        @php
            $totDr = 0;
            $totCr = 0;
        @endphp

        <tbody>
        @foreach($data->trans_detail as $key=>$dtl)
            <tr>
                <td style="text-align: left; padding-left: 5px; padding-right: 5px">{{++$key}}</td>
                <td style="text-align: left; padding-left: 5px; padding-right: 5px">{{$dtl->gl_acc_id}}</td>
                <td style="text-align: left; padding-left: 5px; padding-right: 5px">{{$dtl->gl_coa->gl_acc_name}}</td>
                <td style="text-align: center; padding-left: 5px; padding-right: 5px">{{$dtl->dr_cr}}</td>
                <td style="text-align: right; padding-left: 5px; padding-right: 5px">{{$dtl->dr_cr == 'D' ? number_format($dtl->amount_ccy, 2)  : number_format(0, 2)}}</td>
                <td style="text-align: right; padding-left: 5px; padding-right: 5px">{{$dtl->dr_cr == 'C' ? number_format($dtl->amount_ccy, 2) : number_format(0, 2)}}</td>
                @php
                    $totDr += $dtl->dr_cr == 'D' ? $dtl->amount_ccy  : 0.00;
                    $totCr += $dtl->dr_cr == 'C' ? $dtl->amount_ccy  : 0.00;
                @endphp
            </tr>
        @endforeach
        </tbody>

        <thead>
        <tr>
            <th colspan="4">Total</th>
            <th style="text-align: right;">{{number_format($totDr, 2)}}</th>
            <th style="text-align: right;">{{number_format($totCr, 2)}}</th>
        </tr>
        </thead>

    </table>
</div>
<div style="position: fixed; bottom: 0; width: 100%;">
    <table style="border-collapse: collapse;width: 100%; margin-bottom: 50px; table-layout: fixed; font-size: 13px; ">
        <tr>
            <th>
                <span style="border-top: 1px solid black;">
                    <p>Prepared By</p>
                </span>

            </th>
            <!--<th>
                <span style="border-top: 1px solid black;">
                    <p>Requisition By</p>
                </span>
            </th>-->
            <th>
                <span style="border-top: 1px solid black;">
                    <p>Check By(O/P)</p>
                </span>
            </th>
            <th>
                <span style="border-top: 1px solid black;">
                    <p>Approved By</p>
                </span>
            </th>
        </tr>
    </table>
</div>
