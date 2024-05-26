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
                }
            }
        }

        stage('Build Backend') {
            steps {
                script {
                    // Install composer dependencies on the local machine...
                    sh 'composer install'
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    def remoteDirectory = "/var/www/html"

                    // Print a message indicating that deployment is starting
                    echo "Deploying to remote directory: ${remoteDirectory}"

                    // Clean the remote directory before deploying
                    sh "ssh -o StrictHostKeyChecking=no ubuntu@16.170.210.115 'rm -rf ${remoteDirectory}/*'"

                    // Print a message indicating that cleaning is complete
                    echo "Remote directory cleaned"

                    // Deploy files using sshPublisher
                    sshPublisher(
                        publishers: [
                            sshPublisherDesc(
                                configName: 'ApacheServer', // Replace with your actual SSH server configuration name
                                transfers: [
                                    sshTransfer(
                                        sourceFiles: '**/*',
                                        remoteDirectory: remoteDirectory,
                                        removePrefix: '',
                                        execCommand: ''
                                    )
                                ],
                                usePromotionTimestamp: false,
                                useWorkspaceInPromotion: false,
                                verbose: true
                            )
                        ]
                    )

                    // Print a message indicating that deployment is complete
                    echo "Deployment completed"
                }
            }
        }


        stage('Start Vue Frontend') {
            steps {
                script {
                    sh 'npm run dev'
                }
            }
        }

        stage('Start Laravel Server') {
            steps {
                script {
                    def remoteDirectory = "/var/www/html"

                    // Start the Laravel server on the remote server
                    sh "ssh -o StrictHostKeyChecking=no ubuntu@16.170.210.115 'cd ${remoteDirectory} && nohup php artisan serve --host=0.0.0.0 --port=8000 > /dev/null 2>&1 &'"
                }
            }
        }
    }
}
