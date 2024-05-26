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
                    sh 'npm install'
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
                    def remoteDirectory = "/var/www/html"

                    // Clean the remote directory before deploying
                    sh "ssh -o StrictHostKeyChecking=no ubuntu@16.170.210.115 'rm -rf ${remoteDirectory}/*'"

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
