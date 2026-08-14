<?php
$file = 'modules/AppAscend/Resources/views/livewire/ascend-module-viewer.blade.php';
$lines = file($file);
$stack = [];

foreach ($lines as $num => $line) {
    preg_match_all('/@(if|elseif|else|endif|forelse|empty|endforelse|foreach|endforeach)\b/', $line, $matches);
    foreach ($matches[1] as $tag) {
        $l = $num + 1;
        if (in_array($tag, ['if', 'forelse', 'foreach'])) {
            $stack[] = [$tag, $l];
        } elseif ($tag === 'endif') {
            if (empty($stack)) {
                echo "Extra @endif at line $l\n";
            } else {
                $last = array_pop($stack);
                if ($last[0] !== 'if') {
                    echo "Mismatched @endif at line $l (expected end of {$last[0]} from line {$last[1]})\n";
                }
            }
        } elseif ($tag === 'endforelse') {
            if (empty($stack)) {
                echo "Extra @endforelse at line $l\n";
            } else {
                $last = array_pop($stack);
                if ($last[0] !== 'forelse') {
                    echo "Mismatched @endforelse at line $l (expected end of {$last[0]} from line {$last[1]})\n";
                }
            }
        } elseif ($tag === 'endforeach') {
            if (empty($stack)) {
                echo "Extra @endforeach at line $l\n";
            } else {
                $last = array_pop($stack);
                if ($last[0] !== 'foreach') {
                    echo "Mismatched @endforeach at line $l (expected end of {$last[0]} from line {$last[1]})\n";
                }
            }
        }
    }
}

echo "Remaining unclosed directives: " . count($stack) . "\n";
foreach ($stack as $s) {
    echo "Unclosed @{$s[0]} from line {$s[1]}\n";
}
