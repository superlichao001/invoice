<?php

namespace App\Repositories\Api;

use App\Models\Invoice;
use App\Models\InvoiceCheck;
use App\Repositories\Api\RepositoryInterfaces\InvoiceInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;

class InvoiceService implements InvoiceInterface
{
    const CHECK_URL = 'https://fapiao.market.alicloudapi.com/v2/invoice/query';

    public function invoiceCheck($data)
    {
        $fpdm = isset($data['fpdm']) ? $data['fpdm'] : '';
        $fphm = isset($data['fphm']) ? $data['fphm'] : '';
        $kprq = isset($data['kprq']) ? $data['kprq'] : '';
        $no_tax_amount = isset($data['no_tax_amount']) ? $data['no_tax_amount'] : '';
        $check_code = isset($data['check_code']) ? $data['check_code'] : '';

        if (!$fpdm) {
            return response()->error(1001, '发票代码不能为空');
        }

        if (!$fphm) {
            return response()->error(1002, '发票号码不能为空');
        }

        if (!$kprq) {
            return response()->error(1003, '开票日期不能为空');
        }

        if (!$check_code) {
            return response()->error(1004, '校验码不能为空');
        }

        $unique = md5($fpdm . $fphm . $kprq . $no_tax_amount . $check_code);
        $last_check = InvoiceCheck::where('unique', $unique)->first();
        if ($last_check) {
            $last_check = $last_check->toArray();
            if ($last_check['is_success'] == InvoiceCheck::CHECK_FAILED) {
                return response()->error(1010, $last_check['message'] ?: "发票校验未通过");
            }
            $content = json_decode($last_check['content'], 1);
            $content = $content['data'];

            $count = Invoice::where('check_id', $last_check['id'])->count();
            if (!$count) {
                $res_data = $content;
                $invoice_create = [
                    'check_id' => $last_check['id'],
                    'fpdm' => $res_data['fpdm'],
                    'fphm' => $res_data['fphm'],
                    'fplx' => $res_data['fplx'],
                    'fplx_name' => $res_data['fplxName'],
                    'kprq' => $res_data['kprq'],
                    'check_code' => $check_code ?: '',
                    'no_tax_amount' => $res_data['goodsamount'],
                    'content' => json_encode($res_data),
                    'user_id' => 1,//todo
                    'is_expenses' => Invoice::EXPENSES_NO
                ];

                Invoice::create($invoice_create);
            }

            InvoiceCheck::where('id', $last_check['id'])->update([
                'check_count' => $last_check['check_count'] + 1
            ]);

            return response()->success($content);
        }

        $query = [
            'fpdm' => $fpdm,
            'fphm' => $fphm,
            'kprq' => $kprq,
            'noTaxAmount' => $no_tax_amount,
            'checkCode' => $check_code,
        ];

        $data = [
            'headers' => [
                'Authorization' => 'APPCODE 04287ceaca2a473a86c3e8f9a2aeedfa',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            ],
            'query' => $query,

//            'query' => [
//                'fpdm' => '051001900111',
//                'fphm' => '51096586',
//                'kprq' => '20190608',
//                'noTaxAmount' => '110.72',
//                'checkCode' => '769875',
//            ],
        ];

        try {
            $client = new Client();
            $res = $client->request('POST', self::CHECK_URL, $data);
            $res = json_decode($res->getBody(), 1);

            if (!$res || !isset($res['success'])) {
                return response()->error(1020, '发票校验接口异常');
            }

            $check_create = [
                'unique' => $unique,
                'fpdm' => $fpdm,
                'fphm' => $fphm,
                'kprq' => $kprq,
                'check_code' => $check_code,
                'no_tax_amount' => $no_tax_amount,
                'content' => json_encode($res),
                'request_id' => isset($res['request_id']) ? $res['request_id'] : '',
                'check_count' => 1,
                'real_check_count' => 1,
                'is_success' => $res['success'] ? InvoiceCheck::CHECK_SUCCESS : InvoiceCheck::CHECK_FAILED,
                'code' => isset($res['code']) ? $res['code'] : '',
                'message' => isset($res['message']) ? $res['message'] : '',
                'user_id' => 1,//todo
            ];

            $check_create_res = InvoiceCheck::create($check_create);
            if (!$check_create_res) {
                return response()->error(1020, '发票校验信息记录失败');
            }

            //校验失败
            if (!$res['success']) {
                return response()->error(1021, $res['message']);
            }

            //校验成功
            $res_data = $res['data'];
            $invoice_create = [
                'check_id' => $check_create_res['id'],
                'fpdm' => $res_data['fpdm'],
                'fphm' => $res_data['fphm'],
                'fplx' => $res_data['fplx'],
                'fplx_name' => $res_data['fplxName'],
                'kprq' => $res_data['kprq'],
                'check_code' => $check_code ?: '',
                'no_tax_amount' => $res_data['goodsamount'],
                'content' => json_encode($res_data),
                'user_id' => 1,//todo
                'is_expenses' => Invoice::EXPENSES_NO
            ];

            $invoice_create_res = Invoice::create($invoice_create);
            if (!$invoice_create_res) {
                return response()->error(1030, '发票校验信息记录失败');
            }

            return response()->success($res_data);
        } catch (\Exception $exception) {
            $http_code = $exception->getCode();

            if ($http_code == 400) {
                return response()->error(1060, '发票校验未通过');
            }

            return response()->error(1061, json_encode($exception));
        }
    }
}
