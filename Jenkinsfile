pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Checkout the code from the Git repository
                checkout scm
            }
        }

        stage('Build Frontend') {
            steps {
                script {
                    // Install npm dependencies and run npm build on the local machine
                    sh 'npm install --verbose'
                    sh 'npm run build'
                }
            }
        }

        stage('Build Backend') {
            steps {
                script {
                    // Install composer dependencies on the local machine
                    sh 'composer install'
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    def remoteDirectory = "/var/www/html/boardify"

                    // Print a message indicating that deployment is starting
                    echo "Deploying to remote directory: ${remoteDirectory}"

                    // Use sshagent to provide SSH key for the session
                    sshagent(['Jenkins_SSH_Key']) {
                        // Clean the remote directory before deploying
                        sh "ssh -o StrictHostKeyChecking=no ubuntu@16.170.210.115 'rm -rf ${remoteDirectory}/*'"

                        // Print a message indicating that cleaning is complete
                        echo "Remote directory cleaned"

                        // Deploy files using scp
                        sh "scp -o StrictHostKeyChecking=no -r * ubuntu@16.170.210.115:${remoteDirectory}"
                    }

                    // Print a message indicating that deployment is complete
                    echo "Deployment completed"
                }
            }
        }

        stage('Start Laravel Server') {
            steps {
                script {
                    def remoteDirectory = "/var/www/html/boardify"

                    // Start the Laravel server on the remote server
                    sshagent(['Jenkins_SSH_Key']) {
                        sh "ssh -o StrictHostKeyChecking=no ubuntu@16.170.210.115 'cd ${remoteDirectory} && nohup php artisan serve --host=0.0.0.0 --port=8000 > /dev/null 2>&1 &'"
                    }
                }
            }
        }
    }
}
