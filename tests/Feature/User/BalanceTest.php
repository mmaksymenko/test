<?php

namespace Tests\Feature\User;

use App\Models\User;
use DOMDocument;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class BalanceTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDebit()
    {
        $domDocument = new DOMDocument('1.0');
        $debitElement = $domDocument->createElement('debit');

        $amountAttribute = $domDocument->createAttribute('amount');
        $amountAttribute->value = $amount = $this->faker->randomNumber(4);
        $debitElement->appendChild($amountAttribute);

        $user = User::first();
        $uidAttribute = $domDocument->createAttribute('uid');
        $uidAttribute->value = $uid = $user->id;
        $debitElement->appendChild($uidAttribute);

        $tidAttribute = $domDocument->createAttribute('tid');
        $tidAttribute->value = $tid = str_replace('-', '', Uuid::uuid4());
        $debitElement->appendChild($tidAttribute);

        $domDocument->appendChild($debitElement);

        $response = $this->call('POST', '/api/user/balance', [], [], [], [
            'CONTENT_TYPE' => 'application/xml',
        ], $domDocument->saveXML());

        $response->assertStatus(200);

        $responseStatus = (string) simplexml_load_string($response->getContent())['status'];
        if ($user->balance < $amount) {
            $this->assertEquals($user->balance, $user->fresh()->balance);
            $this->assertEquals('ERROR', $responseStatus);
        } else {
            $this->assertEquals($user->balance - $amount, $user->fresh()->balance);
            $this->assertEquals('OK', $responseStatus);
        }
    }

    public function testCredit()
    {
        $domDocument = new DOMDocument('1.0');
        $creditElement = $domDocument->createElement('credit');

        $amountAttribute = $domDocument->createAttribute('amount');
        $amountAttribute->value = $amount = $this->faker->randomNumber(3);
        $creditElement->appendChild($amountAttribute);

        $user = User::first();
        $uidAttribute = $domDocument->createAttribute('uid');
        $uidAttribute->value = $uid = $user->id;
        $creditElement->appendChild($uidAttribute);

        $tidAttribute = $domDocument->createAttribute('tid');
        $tidAttribute->value = $tid = str_replace('-', '', Uuid::uuid4());
        $creditElement->appendChild($tidAttribute);

        $domDocument->appendChild($creditElement);

        $response = $this->call('POST', '/api/user/balance', [], [], [], [
            'CONTENT_TYPE' => 'application/xml',
        ], $domDocument->saveXML());

        $response->assertStatus(200);

        $responseStatus = (string) simplexml_load_string($response->getContent())['status'];

        $this->assertEquals($user->balance + $amount, $user->fresh()->balance);
        $this->assertEquals('OK', $responseStatus);
    }
}
