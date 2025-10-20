import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/api/client.dart';
import '../../../core/storage/secure_storage.dart';

final secureStorageProvider = Provider((_) => SecureStorage());
final dioProvider = Provider((ref) {
  final storage = ref.read(secureStorageProvider);
  final dio = ApiClient.build();
  dio.interceptors.add(AuthInterceptor(storage));
  return dio;
});

class AuthState {
  final String? accessToken;
  final Map<String, dynamic>? user;
  const AuthState({this.accessToken, this.user});
  bool get isLoggedIn => accessToken != null && accessToken!.isNotEmpty;
}

final authStateProvider = StateProvider<AuthState>((_) => const AuthState());

final authRepoProvider = Provider((ref) => AuthRepo(ref));

class AuthRepo {
  final Ref ref;
  AuthRepo(this.ref);

  Future<void> login(String email, String password) async {
    final dio = ref.read(dioProvider);
    final res = await dio.post('/auth/login', data: {'email': email, 'password': password});
    final token = res.data['token'];
    final access = token['access_token'] as String;
    final refresh = token['refresh_token'] as String;

    final storage = ref.read(secureStorageProvider);
    await storage.write('access_token', access);
    await storage.write('refresh_token', refresh);

    ref.read(authStateProvider.notifier).state = AuthState(
      accessToken: access,
      user: Map<String, dynamic>.from(res.data['user'] as Map),
    );
  }

  Future<void> logout() async {
    final storage = ref.read(secureStorageProvider);
    await storage.delete('access_token');
    await storage.delete('refresh_token');
    ref.read(authStateProvider.notifier).state = const AuthState();
  }
}

