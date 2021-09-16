<?php

function api_usuario_get($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  // api/usuario -> Padrão
  // jwt-auth/v1/token -> Gerar o token
  // eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3JhbmVrX3dvcmRwcmVzcyIsImlhdCI6MTYzMTgzMzEwMCwibmJmIjoxNjMxODMzMTAwLCJleHAiOjE2MzI0Mzc5MDAsImRhdGEiOnsidXNlciI6eyJpZCI6IjQifX19.45EFEccUakFF85yMWWOlgnYNQtjwbRjXWXk_T3kjnDA -> token atual
  if($user_id > 0) {
    $user_meta = get_user_meta($user_id);

    $response = array(
      "id"      => $user->user_login,
      "nome"    => $user->display_name,
      "email"   => $user->user_email,
      "cep"     => $user_meta['cep'][0],
      "numero"  => $user_meta['numero'][0],
      "rua"     => $user_meta['rua'][0],
      "bairro"  => $user_meta['bairro'][0],
      "cidade"  => $user_meta['cidade'][0],
      "estado"  => $user_meta['estado'][0]
    );
  } else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão', array('status' => 401));
  }

  return rest_ensure_response($response);
}

function registrar_api_usuario_get() {
  register_rest_route('api', '/usuario', array(
    array(
      'methods' =>  WP_REST_Server::READABLE,
      'callback' => 'api_usuario_get'
    )
  ));
}

add_action('rest_api_init', 'registrar_api_usuario_get');
?>