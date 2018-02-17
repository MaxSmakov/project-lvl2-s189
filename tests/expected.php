<?php
namespace GenerateDiff\Tests;

const EXPECTED_FLAT = <<<FLAT
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}
FLAT;

const EXPECTED_TREE = <<<TREE
{
    host: hexlet.io
  + timeout: 50
  - timeout: 10
  - get: true
    settings: {
    + timeout: 20
    - timeout: 290
    + speed: medium
    - speed: slow
      standBy: {
      + drums: pearl
      - drums: gretch
      + sticks: tama
      - sticks: vic firth
      - cymbals: zildjian
      }
    }
  + set: false
}
TREE;

const EXPECTED_PLAIN = <<<PLAIN
Property 'timeout' was changed. From '10' to '50'
Property 'get' was removed
Property 'settings.timeout' was changed. From '290' to '20'
Property 'settings.speed' was changed. From 'slow' to 'medium'
Property 'settings.standBy.drums' was changed. From 'gretch' to 'pearl'
Property 'settings.standBy.sticks' was changed. From 'vic firth' to 'tama'
Property 'settings.standBy.cymbals' was removed
Property 'set' was added with value: 'false'
PLAIN;
