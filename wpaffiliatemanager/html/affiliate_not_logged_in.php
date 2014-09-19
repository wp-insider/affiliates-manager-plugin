<?php printf( __( 'This is the affiliates section of this store.  If you are an existing affiliate, please <a href="%s">log in</a> to access your control panel.', 'wpam' ), $this->viewData['loginUrl'] ) ?>
<br />
<br />
<?php printf( __( 'If you are not an affiliate, but wish to become one, you will need to apply.  To apply, you must be a registered user on this blog.  If you have an existing account on this blog, please <a href="%s">log in</a>.  If not, please <a href="%s">register</a>.', 'wpam' ), $this->viewData['loginUrl'], $this->viewData['registerUrl'] );
