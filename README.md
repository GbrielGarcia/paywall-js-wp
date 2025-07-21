# Paywall.js WP

**Plugin oficial para WordPress que integra la librería Paywall.js y permite proteger tus pagos con efectos visuales automáticos en tu sitio web.**

---

## 📦 Descripción

**Paywall.js WP** añade una capa de protección visual a tu sitio WordPress, mostrando efectos de gradiente, sólido o fade cuando un pago está vencido. Es ideal para freelancers, agencias y desarrolladores que desean asegurarse de que sus clientes paguen a tiempo.

- **Fácil de usar:** Configuración desde el panel de administración.
- **Personalizable:** Elige fecha de vencimiento, días de plazo, tipo de efecto y colores.
- **Automático:** El efecto se aplica sin intervención manual.
- **Ligero:** Sin dependencias externas, solo carga el JS necesario.

---

## 🚀 Características

- **3 Efectos Visuales:** Gradiente (por defecto), Sólido, Fade.
- **Configuración simple:** Solo 2 campos obligatorios.
- **Panel de opciones en el admin:** Ajusta todo desde “Ajustes > Paywall.js”.
- **Internacionalización:** Listo para español e inglés.
- **Compatible con cualquier tema moderno de WordPress.**
- **Seguro:** Cumple con las mejores prácticas de WordPress.

---

## 🛠️ Instalación

1. Sube la carpeta `paywall-js-wp` a `/wp-content/plugins/`.
2. Activa el plugin desde el menú “Plugins” de WordPress.
3. Ve a “Ajustes > Paywall.js” para configurar la fecha de vencimiento, días de plazo, efecto y colores.

---

## ⚙️ Configuración

- **Fecha de vencimiento:** Día límite de pago (formato YYYY-MM-DD).
- **Días de plazo:** Días para que el efecto llegue a su máxima intensidad (por defecto: 60).
- **Efecto visual:** `gradient` (gradiente), `solid` (sólido), `fade` (opacidad).
- **Color sólido:** Color hexadecimal para el efecto sólido.
- **Gradiente desde/hasta:** Colores inicial y final para el gradiente.

---

## 🌟 Ejemplo de Uso

Una vez configurado, el plugin inyecta automáticamente el siguiente código en tu sitio:

```js
new Paywall({
  dueDate: '2025-07-15',
  daysDeadline: 10,
  effect: 'gradient', // o 'solid', 'fade'
  color: '#ff0000',
  gradientFrom: '#ff0000',
  gradientTo: '#000000'
});
```

---

## 📷 Capturas de Pantalla

1. Página de configuración en el admin.
2. Ejemplo de overlay gradiente en el frontend.

---

## ❓ Preguntas Frecuentes

**¿Puedo personalizar los colores?**  
Sí, puedes elegir el color sólido y los colores del gradiente desde la configuración.

**¿Funciona con cualquier tema?**  
Sí, el plugin es compatible con cualquier tema moderno de WordPress.

---

## 📝 Changelog

**1.0.0**
- Versión inicial: integración completa de Paywall.js, panel de configuración, efectos gradiente, sólido y fade.

---

## 👨‍💻 Autor y Créditos

- **Autor:** Alberto Guaman ([GbrielGarcia](https://github.com/GbrielGarcia)) - Tinguar
- **Mejoras y publicación:** Alberto Guaman para WordPress

---

## 📄 Licencia

MIT License

---

## 🔗 Enlaces Importantes

- **Repositorio GitHub:** https://github.com/GbrielGarcia/paywall-js
- **Paywall.js en npm:** https://www.npmjs.com/package/paywall-js
- **Sitio de la agencia:** https://tinguar.com

---

¿Listo para proteger tus pagos?  
¡Instala Paywall.js WP y olvídate de los clientes morosos!

¿Necesitas ayuda o soporte?  
Abre un issue en GitHub o contacta a [Tinguar](https://tinguar.com).

¿Quieres continuar el desarrollo?  
¡Este README contiene toda la información esencial para seguir trabajando en el plugin! 

## Desinstalación

Para desinstalar completamente el plugin y eliminar todas sus opciones de la base de datos:

1. Ve a la sección de plugins en el panel de administración de WordPress.
2. Desactiva y elimina el plugin "Paywall.js WP".
3. Al eliminarlo, se borrarán automáticamente las opciones de configuración almacenadas por el plugin.

## Mejores prácticas

- Realiza copias de seguridad antes de instalar o eliminar plugins.
- Mantén el plugin actualizado para recibir mejoras y parches de seguridad.
- Si personalizas el código, documenta los cambios para futuras actualizaciones. 