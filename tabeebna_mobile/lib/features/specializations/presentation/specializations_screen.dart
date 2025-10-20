import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/specializations_repo.dart';

final specializationsProvider = FutureProvider((ref) => ref.read(specializationsRepoProvider).list());

class SpecializationsScreen extends ConsumerWidget {
  const SpecializationsScreen({super.key});
  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(specializationsProvider);
    return Scaffold(
      appBar: AppBar(title: const Text('\u0627\u0644\u062a\u062e\u0635\u0635\u0627\u062a')),
      body: async.when(
        data: (items) => ListView.separated(
          itemCount: items.length,
          separatorBuilder: (_, __) => const Divider(height: 1),
          itemBuilder: (_, i) {
            final s = items[i];
            return ListTile(title: Text(s['name'] ?? ''));
          },
        ),
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, st) => Center(child: Text('Error: $e')),
      ),
    );
  }
}

