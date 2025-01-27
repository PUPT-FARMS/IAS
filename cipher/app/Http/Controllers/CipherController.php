<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CipherController extends Controller
{
    private $grid;

    public function __construct()
    {
        // Define a grid (we'll use letters for simplicity now)
        $this->grid = [
            ['T', 'R', 'S', 'O'],
            ['P', 'A', 'Q', 'L'],
            ['M', 'C', 'E', 'N'],
            ['D', 'F', 'G', 'H'],
        ];
    }

    public function index()
    {
        return view('cipher');
    }

    public function encrypt(Request $request)
    {
        $text = strtoupper($request->input('text')); // Ensure text is in uppercase for consistency
        $key = (int) $request->input('key');
        $encrypted = $this->gridCipher($text, $key);
        return response()->json(['encrypted' => $encrypted]);
    }

    public function decrypt(Request $request)
    {
        $text = strtoupper($request->input('text')); // Ensure text is in uppercase for consistency
        $key = (int) $request->input('key');
        $decrypted = $this->reverseGridCipher($text, $key);
        return response()->json(['decrypted' => $decrypted]);
    }

    // Encrypt the text by converting to the grid positions and applying a shift
    private function gridCipher($text, $key)
    {
        $encrypted = '';
        $gridSize = 4;

        foreach (str_split($text) as $char) {
            // Find the grid coordinates for each character (we're assuming they match grid positions)
            $found = false;
            for ($i = 0; $i < $gridSize; $i++) {
                for ($j = 0; $j < $gridSize; $j++) {
                    if ($this->grid[$i][$j] === $char) {
                        $newRow = ($i + $key) % $gridSize;
                        $newCol = ($j + $key) % $gridSize;
                        $encrypted .= $this->grid[$newRow][$newCol];
                        $found = true;
                        break 2;
                    }
                }
            }
            // If not found in grid, keep original char (or handle otherwise)
            if (!$found) {
                $encrypted .= $char;
            }
        }

        return $encrypted;
    }

    // Reverse the cipher (decrypt)
    private function reverseGridCipher($text, $key)
    {
        $decrypted = '';
        $gridSize = 4;

        foreach (str_split($text) as $char) {
            // Find the grid coordinates for each character (we're assuming they match grid positions)
            $found = false;
            for ($i = 0; $i < $gridSize; $i++) {
                for ($j = 0; $j < $gridSize; $j++) {
                    if ($this->grid[$i][$j] === $char) {
                        $newRow = ($i - $key + $gridSize) % $gridSize;
                        $newCol = ($j - $key + $gridSize) % $gridSize;
                        $decrypted .= $this->grid[$newRow][$newCol];
                        $found = true;
                        break 2;
                    }
                }
            }
            // If not found in grid, keep original char (or handle otherwise)
            if (!$found) {
                $decrypted .= $char;
            }
        }

        return $decrypted;
    }
}