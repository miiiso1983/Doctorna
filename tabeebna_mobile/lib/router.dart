import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'features/auth/presentation/login_screen.dart';
import 'features/specializations/presentation/specializations_screen.dart';

Route<dynamic> onGenerateRoute(RouteSettings settings, WidgetRef ref) {
  switch (settings.name) {
    case '/':
      return MaterialPageRoute(builder: (_) => const LoginScreen());
    case '/login':
      return MaterialPageRoute(builder: (_) => const LoginScreen());
    case '/specializations':
      return MaterialPageRoute(builder: (_) => const SpecializationsScreen());
    default:
      return MaterialPageRoute(builder: (_) => const LoginScreen());
  }
}

