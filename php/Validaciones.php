<?php

function inputsVacios($inputs)
{
  foreach ($inputs as $input) {
    if ($input == '') return true;
  }
  return false;
}
