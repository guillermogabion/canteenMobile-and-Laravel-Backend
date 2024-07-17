import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'main_screen.dart';
import 'register_screen.dart';
import '../api_service.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:canteen_app/widgets/circle_back.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({Key? key}) : super(key: key);

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _nameController = TextEditingController();
  final _passwordController = TextEditingController();
  final ApiService _apiService = ApiService();

  final FocusNode _focusNode1 = FocusNode();
  final FocusNode _focusNode2 = FocusNode();

  bool _isFocused1 = false;
  bool _isFocused2 = false;
  bool _isLoading = false;

  void _login() async {
    setState(() {
      _isLoading = true;
    });

    String name = _nameController.text;
    String password = _passwordController.text;

    try {
      Map<String, dynamic> response = await _apiService.login(name, password);
      setState(() {
        _isLoading = false;
      });

      // Extract access token directly as string
      if (response.containsKey('access_token')) {
        String accessToken = response['access_token'];
        await _saveAccessToken(accessToken);

        // Navigate to HomeScreen upon successful login
        _navigateToHomeScreen(accessToken);
      } else {
        // Handle the case where login was not successful or access_token is missing
        // _showErrorDialog('Invalid Credentials');
        WidgetsBinding.instance.addPostFrameCallback((_) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Failed to Log in. Please try again.'),
            ),
          );
        });
      }
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      // Handle login error
      WidgetsBinding.instance.addPostFrameCallback((_) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Failed to Log in. Please try again.'),
          ),
        );
      });
    }
  }

  Future<void> _saveAccessToken(String accessToken) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();

    // Save the access token to SharedPreferences
    await prefs.setString('access_token', accessToken);
  }

  void _navigateToHomeScreen(String accessToken) {
    if (mounted) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
            builder: (context) => MainScreen(accessToken: accessToken)),
      );
    }
  }

  void _showErrorDialog(String message) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Error'),
          content: Text(message),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: const Text('OK'),
            ),
          ],
        );
      },
    );
  }

  _launchPasswordResetUrl() async {
    const String baseUrl =
        'https://canteen.websystems.site'; // Access baseUrl from ApiService
    // final String baseUrl =
    //     _apiService.baseUrl; // Access baseUrl from ApiService
    final Uri url = Uri.parse('$baseUrl/password/reset');
    if (!await launchUrl(url)) {
      throw Exception('Could not launch $url');
    }
  }

  @override
  void initState() {
    super.initState();
    _focusNode1.addListener(_handleFocusChange1);
    _focusNode2.addListener(_handleFocusChange2);
  }

  void _handleFocusChange1() {
    setState(() {
      _isFocused1 = _focusNode1.hasFocus;
    });
  }

  void _handleFocusChange2() {
    setState(() {
      _isFocused2 = _focusNode2.hasFocus;
    });
  }

  @override
  void dispose() {
    _focusNode1.removeListener(_handleFocusChange1);
    _focusNode1.dispose();
    _focusNode2.removeListener(_handleFocusChange2);
    _focusNode2.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: <Widget>[
            const SizedBox(height: 50),
            const CircleBackButton(),
            const SizedBox(height: 80),
            const Text(
              'Sign In',
              style: TextStyle(
                color: Color.fromARGB(255, 68, 68, 68),
                fontSize: 28,
              ),
            ),
            const SizedBox(height: 30),
            TextField(
              focusNode: _focusNode1,
              controller: _nameController,
              decoration: InputDecoration(
                labelText: 'Username',
                labelStyle: TextStyle(
                  color: _isFocused1
                      ? const Color.fromARGB(255, 216, 58, 58)
                      : Colors.grey,
                ),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10.0),
                  borderSide: const BorderSide(
                    color: Colors.grey,
                    width: 2.0,
                  ),
                ),
                enabledBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10.0),
                  borderSide: const BorderSide(
                    color: Color.fromARGB(255, 248, 247, 247),
                    width: 2.0,
                  ),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10.0),
                  borderSide: const BorderSide(
                    color: Color.fromARGB(255, 216, 58, 58),
                    width: 2.0,
                  ),
                ),
              ),
            ),
            const SizedBox(height: 20),
            TextField(
              focusNode: _focusNode2,
              controller: _passwordController,
              obscureText: true,
              decoration: InputDecoration(
                labelText: 'Password',
                labelStyle: TextStyle(
                  color: _isFocused2
                      ? const Color.fromARGB(255, 216, 58, 58)
                      : Colors.grey,
                ),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10.0),
                  borderSide: const BorderSide(
                    color: Color.fromARGB(255, 255, 255, 255),
                    width: 2.0,
                  ),
                ),
                enabledBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10.0),
                  borderSide: const BorderSide(
                    color: Color.fromARGB(255, 255, 255, 255),
                    width: 2.0,
                  ),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10.0),
                  borderSide: const BorderSide(
                    color: Color.fromARGB(255, 216, 58, 58),
                    width: 2.0,
                  ),
                ),
              ),
            ),
            const SizedBox(height: 10),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 16.0),
              child: ElevatedButton(
                onPressed: _isLoading ? null : _login,
                style: ElevatedButton.styleFrom(
                  primary: const Color.fromARGB(
                      255, 216, 58, 58), // Background color
                  onPrimary: Colors.white, // Text color
                  padding: const EdgeInsets.symmetric(vertical: 15),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(20),
                  ),
                ),
                child: const Text(
                  'Sign In',
                  style: TextStyle(fontSize: 18),
                ),
              ),
            ),
            const SizedBox(height: 150),
            GestureDetector(
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => const RegisterScreen(),
                  ),
                );
              },
              child: Center(
                child: Container(
                  padding: const EdgeInsets.all(16.0),
                  decoration: BoxDecoration(
                    border: Border.all(
                        color: const Color.fromARGB(255, 216, 58, 58)),
                    borderRadius: BorderRadius.circular(10),
                    color: const Color(0xFFFFF3F3),
                  ),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: const [
                      Text(
                        'Don\'t have an account yet?',
                        style: TextStyle(color: Colors.black),
                      ),
                      SizedBox(height: 5),
                      Text(
                        'Create Account',
                        style: TextStyle(
                          color: Color.fromARGB(255, 216, 58, 58),
                          decoration: TextDecoration.underline,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
