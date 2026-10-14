# Add GitLab CI/CD (build + FTP deploy) to a project

Not part of the starter: only for a project hosted on GitLab with a GitLab Runner. `.claude/` is never built or deployed.

`files/.gitlab-ci.yml`:
* `build` (branches `dev`, `master`, `main`): `npm ci` + `npx gulp prod`, keeps `dist/` as artifact (npm cache in `.npm/`);
* `deploy_dev` (branch `dev`) / `deploy_master` (`master`, `main`): uploads the theme with `lftp mirror` to
  `public_html/wp-content/themes/{themeFolder}/`, without dot-files/folders (so `.claude/` is never uploaded), `--delete` removes
  files on the server that are not in the theme any more.

## Add it

1. Copy `files/.gitlab-ci.yml` into the theme root and run `gulp init` (sets the server folder from `themeFolder` in `starter.config.json`).
2. GitLab project -> Settings -> CI/CD -> Variables: `FTP_HOST`, `FTP_USER`, `FTP_PASS` (`master` / `main`) and
   `FTP_HOST_DEV`, `FTP_USER_DEV`, `FTP_PASS_DEV` (`dev`). Mark the passwords as masked.
3. Check before the first run:
   * `image:` is a company runner image (`registry.tsks.me/...`): replace it with an image your runner can pull that has
     **Node.js 20.19+** (build) and `apk` (deploy, e.g. `node:24-alpine`).
   * The deploy uses plain FTP (`set ftp:ssl-allow no`): the password is sent unencrypted. If the host supports it, use FTPS
     (remove that setting) or SFTP (`open sftp://$FTP_HOST`).
   * Optional: add `npm run lint` before `npx gulp prod`, so code with lint errors is never deployed.
