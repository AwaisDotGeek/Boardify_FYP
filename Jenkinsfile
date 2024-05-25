pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Checkout the code from the Git repository
                checkout scm
            }
        }

        stage('Deploy') {
            steps {
                script {
                    def remoteDirectory = "/var/www/html/boardify"
                    
                    // Clean the remote directory before deploying
                    sh "ssh -o StrictHostKeyChecking=no ubuntu@16.170.210.115 'rm -rf ${remoteDirectory}'"

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

        stage('Install Dependencies and Build Frontend') {
            steps {
                script {
                    def remoteDirectory = "/var/www/html/boardify"

                    // Install npm dependencies and run npm build on the remote server
                    sh "ssh -o StrictHostKeyChecking=no ubuntu@16.170.210.115 'cd ${remoteDirectory} && npm install && npm run dev'"
                }
            }
        }

        stage('Start Laravel Server') {
            steps {
                script {
                    def remoteDirectory = "/var/www/html/boardify"

                    // Install composer dependencies and run the Laravel server on the remote server
                    sh "ssh -o StrictHostKeyChecking=no ubuntu@16.170.210.115 'cd ${remoteDirectory} && composer install && nohup php artisan serve --host=0.0.0.0 --port=8000 > /dev/null 2>&1 &'"
                }
            }
        }
    }
}
