# Puesta a punto — publicación segura desde WSL

El repositorio GitHub **no puede leer automáticamente** los archivos que están dentro de WSL. Subir primero los **archivos exactos** y auditados, nunca reconstruirlos a ciegas.

## Cómo trabajar sin tocar los contenedores activos

Trabajar sobre un clon de Git distinto de `~/translogistica-ansible` y de `~/translogistica-utu-candidato`:

```bash
git clone https://github.com/PixelForge-proyecto/Proyecto-2026.git "$HOME/translogistica-repo-publicacion"
cd "$HOME/translogistica-repo-publicacion"
git fetch origin
git switch --track origin/preparacion-despliegue-utu-20261120
```

Antes de copiar cualquier archivo de configuración, comprobar que no incluya credenciales literales, claves, direcciones personales o datos. `proxysql.cnf`, `.env` y respaldos están **prohibidos** en Git. Usar placeholders y documentación de rutas.

Los playbooks locales probados el 20/11/2026 todavía referencian `HOME`, el inventario `laboratorio_local` y volúmenes del candidato. **No presentarlos como instalador UTU listo**.

Una vez copiados los archivos públicos revisados, ejecutar:

```bash
git status --short
git diff --check
git diff --cached --name-only
```

Revisar cada ruta y el contenido del diff antes de `git add`/`git commit`/`git push`. No usar `git add -A` sobre carpetas que contengan secretos. Esta rama está destinada a un PR de revisión; **no fusionar** mientras queden pendientes credenciales, redes, respaldos y prueba real.
