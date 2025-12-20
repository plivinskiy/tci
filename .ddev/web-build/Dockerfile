
#ddev-generated
# You can copy this Dockerfile.example to Dockerfile to add configuration
# or packages or anything else to your webimage
# These additions will be appended last to ddev's own Dockerfile
RUN  echo "export TERM=xterm" >> /etc/bashrc/aliases.bashrc && \
  echo "alias ll='ls -alF'" >> /etc/bashrc/aliases.bashrc && \
  echo "alias la='ls -A'" >> /etc/bashrc/aliases.bashrc && \
  echo "alias l='ls -CF'" >> /etc/bashrc/aliases.bashrc && \
  echo "force_color_prompt=yes $(cat /home/$username/.bashrc)" > /home/$username/.bashrc

ENV NPM_CONFIG_CACHE=/home/$username/.npm

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash \
    \
    && mkdir -p ${NPM_CONFIG_CACHE} \
    && apt-get update && apt-get install -y nodejs \
    && npm i npm -g \
    && npm i grunt -g
