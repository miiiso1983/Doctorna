import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/auth_repo.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});
  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _email = TextEditingController(text: 'patient@doctorna.local');
  final _password = TextEditingController(text: 'admin123');
  bool _loading = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('تسجيل الدخول')),
      body: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 360),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Form(
              key: _formKey,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  TextFormField(
                    controller: _email,
                    decoration: const InputDecoration(labelText: 'البريد الإلكتروني'),
                    validator: (v) => (v==null||v.isEmpty) ? 'مطلوب' : null,
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    controller: _password,
                    decoration: const InputDecoration(labelText: 'كلمة المرور'),
                    obscureText: true,
                    validator: (v) => (v==null||v.isEmpty) ? 'مطلوب' : null,
                  ),
                  const SizedBox(height: 20),
                  FilledButton(
                    onPressed: _loading ? null : () async {
                      if (!_formKey.currentState!.validate()) return;
                      setState(() => _loading = true);
                      try {
                        await ref.read(authRepoProvider).login(_email.text.trim(), _password.text);
                        if (context.mounted) {
                          Navigator.of(context).pushReplacementNamed('/specializations');
                        }
                      } catch (e) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(content: Text('خطأ: ${e.toString()}')),
                        );
                      } finally {
                        if (mounted) setState(() => _loading = false);
                      }
                    },
                    child: _loading ? const SizedBox(height:16,width:16,child:CircularProgressIndicator(strokeWidth:2)) : const Text('دخول'),
                  )
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

