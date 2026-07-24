# Forminator i integracije

Tema automatski kreira pet obrazaca:

- Kontakt
- Newsletter pretplata
- Newsletter – brza prijava
- Prijava za edukaciju
- Prijava za posao

Sva polja, poruke, e-mail obavijesti i spremljene prijave uređuju se u WordPress administraciji pod **Forminator**. Za vanjski servis najjednostavnije je na željenom obrascu uključiti Forminatorov **Webhook** integration i mapirati polja bez promjene teme.

## Analytics

Nakon uspješne AJAX prijave tema šalje:

```js
window.dataLayer.push({
  event: "ingbiro_form_submit",
  form_id: "40",
  form_key: "contact",
  page_path: "/kontakt/"
});
```

Isti payload dostupan je i kao browser event:

```js
window.addEventListener("ingbiro:form:submitted", ({ detail }) => {
  // detail.form_id, detail.form_key, detail.page_path
});
```

Tako se GTM, GA4, Meta Pixel ili drugi analytics mogu povezati bez mijenjanja markup-a pojedinih obrazaca.

## Serverski API/CRM hook

Nakon uspješnog spremanja teme emitira WordPress action:

```php
add_action(
	'ingbiro_form_submission',
	function ( $form_id, $form_key, $response ) {
		// Pozovite CRM/API preko wp_remote_post().
	},
	10,
	3
);
```

Za tajnu API konfiguraciju koristite environment varijable ili server-side WordPress opcije; ključeve ne spremati u temu ni Git repozitorij.
