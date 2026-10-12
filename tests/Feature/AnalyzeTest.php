<?php

namespace Tests\Feature;

use App\Ai\Agents\SalesCoach;
use Tests\TestCase;

class AnalyzeTest extends TestCase
{
    public function test_it_analyzes_a_sales_transcript(): void
    {
        SalesCoach::fake(['Tip 1: Ask more open questions.']);

        $this->postJson('/api/analyze', ['transcript' => 'Customer said the price is too high'])
            ->assertOk();

        SalesCoach::assertPrompted('Customer said the price is too high');
    }
}

