import React, { FormEvent } from 'react';
import { router } from '@inertiajs/react'

interface LoginLinkProps {
    className?: string;
    email?: string;
    guard?: string;
    keyId?: string;
    label?: string;
    redirectUrl?: string;
    userAttributes?: Record<string, any>;
}

export default function LoginLink({
    className = 'underline',
    email = null,
    guard = null,
    keyId = null,
    label = 'Login',
    redirectUrl = null,
    userAttributes = null,
}: LoginLinkProps) {
    function submit(event: FormEvent) {
        event.preventDefault();
        router.post(route('loginLinkLogin'), {
            email: email,
            key: keyId,
            redirect_url: redirectUrl,
            guard: guard,
            user_attributes: userAttributes,
        });
    };

    return (
        <form onSubmit={submit}>
            <button className={className} type="submit">
                {label}
            </button>
        </form>
    );
};