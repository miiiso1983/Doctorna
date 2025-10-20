import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../auth/data/auth_repo.dart';

final specializationsRepoProvider = Provider((ref) => SpecializationsRepo(ref));

class SpecializationsRepo {
  final Ref ref;
  SpecializationsRepo(this.ref);

  Future<List<Map<String, dynamic>>> list() async {
    final dio = ref.read(dioProvider);
    final Response res = await dio.get('/specializations');
    final data = (res.data['data'] as List).cast<Map>().map((e)=>Map<String,dynamic>.from(e)).toList();
    return data;
  }
}

