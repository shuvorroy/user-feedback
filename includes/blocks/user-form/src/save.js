import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
export default function Save({ attributes }) {
    return (
        <div {...useBlockProps.save()}>
           <form action="" className='user-feedback' novalidate>
                <h3>{attributes.formTitle}</h3>
                <input type="hidden" name="action" value="submit_feedback" />
                <div className="two-cols">
                    <label>
                        {__('First Name *', 'user-feedback')}
                        <input type="text" name="firstname" value="[user_firstname]" required />
                    </label>
                    <label>
                        {__('Last Name *', 'user-feedback')}
                        <input type="text" name="lastname" value="[user_lastname]" required />
                    </label>
                </div>
                <div className="one-cols">
                    <label>
                        {__('Email *', 'user-feedback')}
                        <input type="email" name="email" value="[user_email]" required />
                    </label>
                </div>
                <div className="one-cols">
                    <label>
                        {__('Subject *', 'user-feedback')}
                        <input type="text" name="subject" required />
                    </label>
                </div>
                <div className="one-cols">
                    <label>
                        {__('Message *', 'user-feedback')}
                    </label>
                    <textarea title='Messages' type="text" name="message" required />
                </div>
                <div className="btns">
                    <button type='submit'>{attributes.buttonText}</button>
                </div>
                <div className="loader-container">
                    <div className="loader" />
                </div>
            </form>
        </div>
    );
}