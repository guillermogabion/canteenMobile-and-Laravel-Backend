import 'package:flutter/material.dart';
import 'package:canteen_app/screens/login_screen.dart';

class StartHomeScreen extends StatefulWidget {
  const StartHomeScreen({Key? key}) : super(key: key);

  @override
  _StartHomeScreenState createState() => _StartHomeScreenState();
}

class _StartHomeScreenState extends State<StartHomeScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              // Logo and Tagline
              Column(
                children: [
                  Image.asset('assets/images/logo.png', height: 150),
                  const SizedBox(height: 20),
                ],
              ),
              const SizedBox(height: 50),
              // Get Started Button
              ElevatedButton(
                onPressed: () {
                  // Navigate to the Login screen
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => const LoginScreen()),
                  );
                },
                style: ElevatedButton.styleFrom(
                  primary: const Color.fromARGB(255, 216, 58, 58),
                  padding:
                      const EdgeInsets.symmetric(horizontal: 50, vertical: 15),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(30),
                  ),
                ),
                child: const Text(
                  'Get Started',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 18,
                  ),
                ),
              ),
              const SizedBox(height: 20),
              // Sign In Link
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Text('Have an Account?'),
                  TextButton(
                    onPressed: () {
                      // Navigate to the Login screen
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const LoginScreen()),
                      );
                    },
                    child: const Text(
                      'Sign In',
                      style: TextStyle(
                        color: Color.fromARGB(255, 216, 58, 58),
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
