import "./bootstrap";
import Alpine from "alpinejs";
import angular from "angular";

window.Alpine = Alpine;

Alpine.start();

var app = angular.module("socialApp", []);
app.controller("PostController", function ($scope, $http) {
    $scope.posts = [];
    $scope.newPost = {};

    $scope.getPosts = function () {
        $http.get("/posts").then(
            function (response) {
                $scope.posts = response.data;
            },
            function (error) {
                console.error("Error fetching posts:", error);
                alert("Error fetching posts");
            }
        );
    };

    $scope.createPost = function () {
        $http.post("/posts", $scope.newPost).then(
            function (response) {
                $scope.posts.unshift(response.data);
                $scope.newPost = {}; // Clear form
            },
            function (error) {
                console.error("Error creating post:", error);
                alert("Error creating post");
            }
        );
    };

    $scope.likePost = function (post) {
        $http.post("/posts/" + post.id + "/like").then(
            function (response) {
                // Toggle like/unlike logic based on the response
                if (response.data.message === "Post liked") {
                    post.likes_count++;
                    post.userHasLiked = true; // Keep track that the user has liked the post
                } else if (response.data.message === "Post unliked") {
                    post.likes_count--;
                    post.userHasLiked = false; // Keep track that the user has unliked the post
                }
            },
            function (error) {
                console.error("Error toggling like:", error);
                alert("Error toggling like");
            }
        );
    };

    $scope.addComment = function (post) {
        $http
            .post("/posts/" + post.id + "/comment", {
                comment: post.newComment,
            })
            .then(
                function (response) {
                    post.comments.push(response.data); // Add the new comment to the post's comments array
                    post.newComment = ""; // Clear the input field after successful comment submission
                },
                function (error) {
                    console.error("Error adding comment:", error);
                    alert("Error adding comment");
                }
            );
    };

    $scope.deletePost = function (post) {
        if (confirm("Are you sure you want to delete this post?")) {
            $http.delete("/posts/" + post.id).then(
                function (response) {
                    // Remove the post from the list after deletion
                    var index = $scope.posts.indexOf(post);
                    if (index > -1) {
                        $scope.posts.splice(index, 1);
                    }
                    alert(response.data.message);
                },
                function (error) {
                    console.error("Error deleting post:", error);
                    alert("Error deleting post");
                }
            );
        }
    };

    $scope.getPosts(); // Fetch posts when the controller initializes
});

app.config(function ($httpProvider) {
    $httpProvider.defaults.headers.common["X-CSRF-TOKEN"] = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
});

app.controller('NotificationController', function ($scope, $http) {
   $scope.notifications = [];
   $scope.unreadCount = 0;

   $scope.loadNotifications = function () {
       $http.get('/notifications').then(function (response) {
           $scope.notifications = response.data;
            $scope.unreadNotification();
       }, function (error) {
           console.error('Error fetching notifications:', error);
           alert('Error fetching notifications');
       });
   };

   $scope.unreadNotification = function () {
       $http.get('/notifications/unread').then(function (response) {
           $scope.unreadCount = response.data.count;
       }, function (error) {
           console.error('Error fetching unread notifications:', error);
           alert('Error fetching unread notifications');
       });
   };

   $scope.markAsRead = function (id) {
       $http.post('/notifications/mark-all-as-read').then(function (response) {
           $scope.loadNotifications();
       }, function (error) {
           console.error('Error marking all notifications as read:', error);
           alert('Error marking all notifications as read');
       });
   };

   $scope.markAllAsRead = function () {
       $http.post('/notifications/mark-all-as-read').then(function (response) {
           $scope.loadNotifications();
       }, function (error) {
           console.error('Error marking all notifications as read:', error);
           alert('Error marking all notifications as read');
       });
   };



//    Echo.channel(`notifications.`)
//     .listen('.notification.created', (e) => {
//         console.log(e.notification);
//         $scope.notifications.unshift(e.notification);
//         $scope.unreadCount++;
//         $scope.apply();
//    })

   Echo.channel(`notifications.`)
    .listen('NotificationEvent', (e) => {
        console.log(e.notification);
        $scope.notifications.unshift(e.notification);
        $scope.unreadCount++;
        $scope.apply();
   })

   $scope.loadNotifications();

});
