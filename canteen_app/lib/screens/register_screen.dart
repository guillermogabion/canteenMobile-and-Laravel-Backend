import 'dart:convert';
import 'dart:io';
import 'dart:typed_data'; // Import dart:typed_data
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter/foundation.dart'; // Required for kIsWeb
import 'package:http/http.dart' as http; // For making HTTP requests
import 'package:canteen_app/api_service.dart'; // Adjust import path as per your project structure
import 'package:canteen_app/screens/main_screen.dart'; // Import home screen

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({Key? key}) : super(key: key);

  @override
  _RegisterScreenState createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _confirmPasswordController =
      TextEditingController();

  File? _profileImage;
  Uint8List? _webImage;
  String? _fileName;
  final ApiService _apiService = ApiService(); // Instance of ApiService

  Future<void> _pickImage() async {
    final pickedFile =
        await ImagePicker().pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      if (kIsWeb) {
        final bytes = await pickedFile.readAsBytes();
        setState(() {
          _webImage = bytes;
          _profileImage = File('web_image'); // Dummy file for web
          _fileName = pickedFile.name; // Save file name for web
        });
      } else {
        setState(() {
          _profileImage = File(pickedFile.path);
          _fileName = pickedFile.name; // Save file name for non-web
        });
      }
    }
  }

  void _register() {
    if (_profileImage == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Please select a profile image'),
        ),
      );
      return;
    }

    String password = _passwordController.text;
    String confirmPassword = _confirmPasswordController.text;

    if (password != confirmPassword) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Passwords do not match'),
        ),
      );
      return;
    }

    // Construct userData map
    Map<String, dynamic> userData = {
      'firstName': _firstNameController.text,
      'lastName': _lastNameController.text,
      'name': _nameController.text,
      'address': _addressController.text,
      'email': _emailController.text,
      'password': _passwordController.text,
      'password_confirmation': _confirmPasswordController.text,
    };

    _performRegistration(userData);
  }

  Future<void> _performRegistration(Map<String, dynamic> userData) async {
    try {
      // Call register method from ApiService
      Map<String, dynamic> response = await _apiService.register(
          userData, _profileImage!, _fileName!, _webImage);

      // Handle successful registration
      print('User registered successfully: $response');

      // Navigate to HomeScreen upon successful registration
      _navigateToHomeScreen(response['access_token']);
    } catch (e) {
      // Handle registration failure
      print('Failed to register user: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Failed to register user. Please try again.'),
        ),
      );
    }
  }

  void _navigateToHomeScreen(String accessToken) {
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(
        builder: (context) => MainScreen(accessToken: accessToken),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: <Widget>[
            const SizedBox(height: 50),
            Container(
              alignment: Alignment.centerLeft,
              child: Container(
                padding: const EdgeInsets.all(
                    2.0), // Optional: to give some padding between the border and the icon
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(
                    color: const Color.fromARGB(
                        255, 238, 230, 230), // Set the color of the border
                    width: 0.2, // Set the width of the border
                  ),
                ),
                child: CircleAvatar(
                  radius: 20.0, // Adjust the radius to make the circle smaller
                  backgroundColor: Colors.transparent,
                  child: SizedBox(
                    width:
                        36.0, // Set the width of the IconButton to match the smaller circle
                    height:
                        36.0, // Set the height of the IconButton to match the smaller circle
                    child: IconButton(
                      iconSize: 15.0, // Set the size of the icon to be smaller
                      icon: Transform.translate(
                        offset:
                            const Offset(0, 0), // Adjust the offset if needed
                        child: const Icon(Icons.arrow_back_ios_new),
                      ),
                      onPressed: () {
                        Navigator.of(context).pop();
                      },
                    ),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 50.0),
            GestureDetector(
              onTap: _pickImage,
              child: _profileImage == null
                  ? Container(
                      color: Colors.grey[300],
                      height: 150,
                      width: 50,
                      child: const Icon(Icons.add_a_photo, size: 50),
                    )
                  : kIsWeb
                      ? Image.memory(_webImage!, height: 150)
                      : Image.file(_profileImage!, height: 150),
            ),
            const SizedBox(height: 24.0),
            TextFormField(
              controller: _firstNameController,
              decoration: const InputDecoration(labelText: 'First Name'),
            ),
            const SizedBox(height: 12.0),
            TextFormField(
              controller: _lastNameController,
              decoration: const InputDecoration(labelText: 'Last Name'),
            ),
            const SizedBox(height: 12.0),
            TextFormField(
              controller: _nameController,
              decoration: const InputDecoration(labelText: 'Username'),
            ),
            const SizedBox(height: 12.0),
            TextFormField(
              controller: _addressController,
              decoration: const InputDecoration(labelText: 'Address'),
            ),
            const SizedBox(height: 12.0),
            TextFormField(
              controller: _emailController,
              decoration: const InputDecoration(labelText: 'Email'),
            ),
            const SizedBox(height: 12.0),
            TextFormField(
              controller: _passwordController,
              obscureText: true,
              decoration: const InputDecoration(labelText: 'Password'),
            ),
            const SizedBox(height: 12.0),
            TextFormField(
              controller: _confirmPasswordController,
              obscureText: true,
              decoration: const InputDecoration(labelText: 'Confirm Password'),
            ),
            const SizedBox(height: 24.0),
            ElevatedButton(
              onPressed: _register,
              style: ElevatedButton.styleFrom(
                primary:
                    const Color.fromARGB(255, 216, 58, 58), // Background color
                onPrimary: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(20),
                ), // Text color
              ),
              child: const Text('Register'),
            ),
          ],
        ),
      ),
    );
  }
}
