import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart'; // Make sure to import url_launcher

class ForgotPasswordScreen extends StatelessWidget {
  final String baseUrl;

  const ForgotPasswordScreen({Key? key, required this.baseUrl})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Forgot Password'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            const Text(
              'Forgot your password?',
              style: TextStyle(fontSize: 24),
            ),
            ElevatedButton(
              onPressed: () {
                _launchPasswordResetUrl(baseUrl);
              },
              child: const Text('Reset Password'),
            ),
            const SizedBox(height: 16),
            const Center(
              child: Padding(
                padding: EdgeInsets.all(8.0),
                child: Text(
                  'New user? Register here',
                  style: TextStyle(
                    color: Colors.blue,
                    decoration: TextDecoration.underline,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _launchPasswordResetUrl(String baseUrl) async {
    final Uri url = Uri.parse('$baseUrl/password/reset');
    if (await canLaunchUrl(url)) {
      await launchUrl(url);
    } else {
      throw 'Could not launch $url';
    }
  }
}
