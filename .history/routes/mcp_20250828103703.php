<?php

use App\MCP\BoilerplateMcpService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MCP Routes
|--------------------------------------------------------------------------
|
| Here is where you can register MCP routes for your application. These
| routes are loaded by the MCP RouteServiceProvider and assigned to the "mcp"
| middleware group. Make something great!
|
 */

Route::mcp(BoilerplateMcpService::class);
