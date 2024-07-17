import 'dart:convert';
import 'dart:io';
import 'dart:typed_data'; // Import dart:typed_data
import 'package:flutter/foundation.dart'; // Required for kIsWeb
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';
import 'package:url_launcher/url_launcher.dart';

class ApiService {
  final String baseUrl = 'http://192.168.93.182:8000';
  final String apiUrl =
      'http://192.168.93.182:8000/api'; // Replace with your actual API URL
  // 'https://canteen.websystems.site/api'; // Replace with your actual API URL

  String getPasswordResetUrl() {
    return '$baseUrl/password/reset';
  }

  Future<void> openPasswordResetUrl() async {
    final url = getPasswordResetUrl();
    if (await canLaunch(url)) {
      await launch(url);
    } else {
      throw 'Could not launch $url';
    }
  }

  Future<Map<String, dynamic>> register(Map<String, dynamic> userData,
      File profileImage, String fileName, Uint8List? webImage) async {
    var request = http.MultipartRequest('POST', Uri.parse('$apiUrl/register'));

    request.fields
        .addAll(userData.map((key, value) => MapEntry(key, value.toString())));

    if (kIsWeb) {
      if (webImage != null) {
        request.files.add(http.MultipartFile.fromBytes(
          'profile',
          webImage,
          filename: fileName,
          contentType: MediaType('image', 'jpeg'), // Use MediaType object
        ));
      }
    } else {
      request.files.add(await http.MultipartFile.fromPath(
        'profile',
        profileImage.path,
        contentType: MediaType('image', 'jpeg'), // Use MediaType object
      ));
    }

    var streamedResponse = await request.send();
    var response = await http.Response.fromStream(streamedResponse);

    if (response.statusCode == 201) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to register user');
    }
  }

  Future<Map<String, dynamic>> login(String name, String password) async {
    final response = await http.post(
      Uri.parse('$apiUrl/login'),
      headers: {
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        'name': name,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Invalid Credentials');
    }
  }

  Future<Map<String, dynamic>> fetchSelf(String token) async {
    final response = await http.get(
      Uri.parse('$apiUrl/self'), // Replace with your actual user info endpoint
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      Map<String, dynamic> responseBody = jsonDecode(response.body);
      if (responseBody.containsKey('user')) {
        return responseBody['user']; // Return the user object
      } else {
        throw Exception('User data not found in response');
      }
    } else {
      throw Exception('Failed to fetch user data');
    }
  }

  // Create
  Future<Map<String, dynamic>> createItem(
      String token, Map<String, dynamic> item) async {
    final response = await http.post(
      Uri.parse(
          '$apiUrl/items'), // Replace with your actual create item endpoint
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode(item),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to create item');
    }
  }

  // Read
  Future<Map<String, dynamic>> readItem(String token, int itemId) async {
    final response = await http.get(
      Uri.parse(
          '$apiUrl/items/$itemId'), // Replace with your actual read item endpoint
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to fetch item');
    }
  }

  // Update
  Future<Map<String, dynamic>> updateItem(
      String token, int itemId, Map<String, dynamic> item) async {
    final response = await http.put(
      Uri.parse(
          '$apiUrl/items/$itemId'), // Replace with your actual update item endpoint
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode(item),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to update item');
    }
  }

  // Delete
  Future<void> deleteItem(String token, int itemId) async {
    final response = await http.delete(
      Uri.parse(
          '$apiUrl/items/$itemId'), // Replace with your actual delete item endpoint
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode != 204) {
      throw Exception('Failed to delete item');
    }
  }

  Future<List<String>> fetchMealImages(String token) async {
    try {
      final response = await http.get(
        Uri.parse('$apiUrl/get-today'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        List<dynamic> todayMealsData = jsonDecode(response.body)['today'];

        List<String> mealImageUrls = todayMealsData.map((todayMealData) {
          String imageUrl =
              '$baseUrl/meal/${todayMealData['todaymeal']['image']}';
          return imageUrl;
        }).toList();

        return mealImageUrls;
      } else {
        throw Exception('Failed to fetch meal images');
      }
    } catch (e) {
      throw Exception('Failed to fetch meal images: $e');
    }
  }

  Future<List<Map<String, dynamic>>> fetchTodayMeals(String token) async {
    try {
      final response = await http.get(
        Uri.parse('$apiUrl/get-today'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        List<dynamic> todayMeals = jsonDecode(response.body)['today'];

        List<Map<String, dynamic>> meals = todayMeals.map((meal) {
          return {
            'id': meal['id'],
            'meal_id': meal['meal_id'],
            'date_available': meal['date_available'],
            'todaymeal': {
              'id': meal['todaymeal']['id'],
              'name': meal['todaymeal']['name'],
              'image': '$baseUrl/meal/${meal['todaymeal']['image']}',
              'description': meal['todaymeal']['description'],
              'price': meal['todaymeal']['price'],
              'created_at': meal['todaymeal']['created_at'],
              'updated_at': meal['todaymeal']['updated_at'],
            },
          };
        }).toList();

        return meals;
      } else {
        throw Exception('Failed to fetch today meals: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Failed to fetch today meals: $e');
    }
  }
}
