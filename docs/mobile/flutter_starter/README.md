Quick start (Flutter starter to paste over a new project)

1) Create the Flutter project locally

   flutter create tabeebna_mobile
   cd tabeebna_mobile
   flutter pub add flutter_riverpod dio flutter_secure_storage

2) Copy the lib/ files from docs/mobile/flutter_starter/lib into your project's lib

3) Configure API base URL
   - Open lib/core/api/config.dart and set ApiConfig.baseUrl to your server /api/v1

4) Run the app

   flutter run

Notes
- Default login values in the form are patient@doctorna.local / admin123
- After login, it navigates to the Specializations list fetched from /specializations
- This is a minimal starter (Material 3, Riverpod). You can evolve routing, theming, i18n, etc.

