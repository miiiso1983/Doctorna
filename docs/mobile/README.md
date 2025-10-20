# Tabeebna Mobile (Flutter) - Plan and Setup

This document outlines the recommended stack, packages, and step-by-step commands to scaffold the Flutter app that consumes the PHP REST API (/api/v1).

## Stack
- Flutter 3.22+
- State mgmt: Riverpod
- Routing: go_router
- HTTP: Dio + Retrofit (or Chopper)
- Models: freezed + json_serializable
- Secure storage: flutter_secure_storage
- i18n: intl + flutter_localizations (ar/en, RTL)
- Maps: google_maps_flutter, geolocator, permission_handler
- Push: firebase_messaging (FCM)

## Create project
```
flutter create tabeebna_mobile
cd tabeebna_mobile
```

## Add packages
```
flutter pub add flutter_riverpod go_router dio retrofit json_annotation build_runner freezed_annotation freezed
flutter pub add flutter_secure_storage shared_preferences intl
flutter pub add google_maps_flutter geolocator permission_handler
flutter pub add firebase_core firebase_messaging
```

For iOS/Android setup, follow each plugin's README (location permissions, Google Maps key, FCM setup, etc.).

## Project structure (suggested)
```
lib/
  app.dart
  main.dart
  core/{api,auth,router,theme,i18n}
  features/{auth,doctors,search,slots,appointments,profile}
  shared/{widgets,utils}
```

## Environment & base URL
Create `lib/core/api/config.dart`:
```dart
class ApiConfig { static const baseUrl = 'https://phpstack-1510634-5887004.cloudwaysapps.com'; }
```

## Retrofit/Dio client (example)
Create `lib/core/api/client.dart`:
```dart
import 'package:dio/dio.dart';
class ApiClient { static Dio build(String token) => Dio(BaseOptions(
  baseUrl: '${ApiConfig.baseUrl}/api/v1',
  headers: {'Authorization': 'Bearer $token'},
)); }
```

## First screens (MVP order)
1) Auth (login + token storage/refresh)
2) Patient: search doctors → doctor details → slots → book appointment
3) Appointments list (upcoming/past)
4) Doctor: appointments list, slot management (phase 2)

## Tokens storage
Use `flutter_secure_storage` for `access_token` and `refresh_token`. Implement interceptor for automatic refresh.

## i18n & RTL
- Add `flutter_localizations` to `MaterialApp`
- Prepare `lib/l10n/arb` for `ar` and `en`. Use `Directionality.of(context)` or `Locale('ar')` for RTL.

## Push notifications
- Configure Firebase project, add iOS/Android apps, download `google-services.json` and `GoogleService-Info.plist`.
- Initialize `firebase_core`, request permission, send FCM token to backend via `/api/v1/notifications/register-device`.

## Maps & location
- Get location with `geolocator` (runtime permission)
- Display with `google_maps_flutter`
- Query backend: `GET /api/v1/doctors?lat=..&lng=..&radius_km=..`

## OpenAPI
See `docs/openapi.yaml` for API contract. Start with: auth, specializations, doctors, slots, appointments.

## Build/run
```
flutter run -d chrome   # Web (for quick UI)
flutter run -d ios      # iOS simulator (after cocoapods)
flutter run -d android  # Android emulator/device
```

## Notes
- Every change to `.env` on the server requires PHP-FPM restart.
- Prefer UTC timestamps in API; convert on device.
- Paginate lists; return consistent JSON envelopes.

