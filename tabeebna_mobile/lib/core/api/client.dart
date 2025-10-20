import 'package:dio/dio.dart';
import 'config.dart';
import '../storage/secure_storage.dart';

class ApiClient {
  static Dio build({String? token}) {
    final dio = Dio(BaseOptions(
      baseUrl: ApiConfig.baseUrl,
      connectTimeout: const Duration(seconds: 20),
      receiveTimeout: const Duration(seconds: 20),
      headers: {
        'Content-Type': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      },
    ));
    return dio;
  }
}

class AuthInterceptor extends Interceptor {
  final SecureStorage storage;
  AuthInterceptor(this.storage);

  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    final access = await storage.read('access_token');
    if (access != null && access.isNotEmpty) {
      options.headers['Authorization'] = 'Bearer $access';
    }
    super.onRequest(options, handler);
  }
}

