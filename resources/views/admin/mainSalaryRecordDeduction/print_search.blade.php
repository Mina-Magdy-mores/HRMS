<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title> بحث فواتير المبيعات</title>
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <link rel="stylesheet" href="{{ asset('assets/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
   <style>
      @media print {
         .hidden-print {
            display: none;
         }
      }

      @media print {
         #printButton {
            display: none;
         }
      }

      td {
         font-size: 15px !important;
         text-align: center;
      }
      th{
         text-align: center;
      }
   </style>

<body style="padding-top: 10px;font-family: tahoma;">


   <table style="width: 60%;float: right;  margin-right: 5px;" dir="rtl">
      <tr>
         <td style="text-align: center;padding: 5px;"> <span> نوع التقرير </span></td>
      </tr>
      <tr>
         <td style="text-align: center;padding: 5px;font-weight: bold;"> <span style=" display: inline-block;
               width: 400px;
               height: 30px;
               text-align: center;
               color: red;
               border: 1px solid black; ">
               بحث بحركة فواتير المبيعات
            </span>
         </td>
      </tr>
      <tr>
         <td style="text-align: center;padding: 5px;font-weight: bold;">
            <span style=" display: inline-block;
                  width: 200px;
                  height: 30px;
                  text-align: center;
                  color: blue;
                  border: 1px solid black; ">
               طبع بتاريخ @php echo date('Y-m-d'); @endphp
            </span>
         </td>
      </tr>
      <tr>
         <td style="text-align: center;padding: 5px;font-weight: bold;">
            <span style=" display: inline-block;
                  width: 200px;
                  height: 30px;
                  text-align: center;
                  color: blue;
                  border: 1px solid black; ">
               طبع بواسطة {{ auth()->user()->name }}
            </span>
         </td>
      </tr>
   </table>
   <table style="width: 35%;float: right; margin-left: 5px; " dir="rtl">
      <tr>
         <td style="text-align:left !important;padding: 5px;">
            <img style="width: 150px; height: 110px; border-radius: 10px;"
               src="{{ asset('assets/admin/uploads').'/'.$systemData['photo'] }}">
            <p>{{ $systemData['system_name'] }}</p>
         </td>
      </tr>
   </table>

   <br>

   @if (@isset($data) && !@empty($data) && count($data)>0)
   <table dir="rtl" id="example2" class="table table-bordered table-hover" style="width: 99%;margin: 0 auto;">
      <thead style="background-color: yellow">
        
            <th style="width: 10%;">كود</th>
            <th style="width: 20%;"> العميل</th>
            <th style="width: 10%;"> فئة</th>
            <th style="width: 10%;" >   الدفع</th>
            <th style="width: 10%;">    اجمالي </th>
            <th style="width: 10%;">المدفوع</th>
            <th style="width: 10%;">الآجل</th>
            <th style="width: 10%;">الحالة </th>
            <th style="width: 10%;">التاريخ </th>
     
         </thead>


      </thead>
      <tbody>
         @foreach ($data as $info )
         <tr>
            <td>{{ $info->auto_serial }}</td>
                     
                        <td>{{ $info->customer_name }}</td>
                        <td>{{ $info->Sales_matrial_types_names}}</td>
                        <td>@if($info->pill_type==1)  كاش  @elseif($info->pill_type==2)  اجل  @else  غير محدد @endif</td>
                        <td>{{ $info->total_cost*1 }}</td>
                        <td>{{ $info->what_paid*1 }}</td>
                        <td>{{ $info->what_remain*1 }}</td>
                        <td>@if($info->is_approved==1)  معتمدة   @else   مفتوحة @endif</td>
                        <td>{{ $info->invoice_date }}</td>

         </tr>
         @endforeach
         <tr>
   <td style="background-color:lightsalmon;" colspan="4"> الاجمالي 

</td>
<td style="background-color: lightgreen;text-align: right; " > 
{{ $total_sum*1 }} جنيه
</td>
<td style="background-color: lightgreen;text-align: right" > 
   {{ $total_whatpaid_sum*1 }} جنيه
   </td>
   <td style="background-color: lightgreen;text-align: right" colspan="3s" > 
      {{ $total_whatremai_sum*1 }} جنيه
      </td>
         </tr>
      </tbody>
   </table>
   <br>

   @else
 <div class="clearfix"></div>
      <p class="" style="text-align: center; font-size: 16px;font-weight: bold; color: brown">
      عفوا لاتوجد بيانات لعرضها !!
      </p>

   @endif


   <br>
   <p style="
         padding: 10px 10px 0px 10px;
         bottom: 0;
         width: 100%;
         /* Height of the footer*/ 
         text-align: center;font-size: 16px; font-weight: bold;
         "> {{ $systemData['address'] }} - {{ $systemData['phone'] }} </p>
   <div class="clearfix"></div> <br>
   <p class="text-center">
      <button onclick="window.print()" class="btn btn-success btn-sm" id="printButton">طباعة</button>
   </p>
</body>

</html>