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
    // You can keep user data in memory later via a provider if needed.
  }

  Future<void> logout() async {
    final storage = ref.read(secureStorageProvider);
    await storage.delete('access_token');
    await storage.delete('refresh_token');
  }
}

